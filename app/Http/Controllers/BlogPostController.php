<?php

namespace App\Http\Controllers;

use App\Jobs\GeneratePostJob;
use App\Models\BlogPost;
use App\Repositories\BlogPostRepository;
use App\Services\AIService;
use App\Services\APIService;
use App\Services\BlogPostService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthUser;
use Illuminate\Support\Facades\Cache;
use Log;

class BlogPostController extends Controller
{
    protected $service;
    protected $ai_service;
    protected $api_service;

    public function __construct(BlogPostService $service, AIService $ai_service, APIService $api_service)
    {
        $this->service = $service;
        $this->ai_service = $ai_service;
        $this->api_service = $api_service;
    }

    public function dashboard(Request $request)
    {
        try {
            $platform_id = $request->get('platform_id') ?? null;
            $status = $request->get('status') ?? null;

            trackInfo('Loading blog posts for platform', ['platform_id' => $platform_id]);

            return view('blog_posts.list', compact('platform_id'));
        } catch (\Exception $e) {
            trackError('Error loading dashboard', ['error' => $e->getMessage()]);
            return redirect()->route('error.page')->with('error', 'Unable to load dashboard');
        }

    }

    public function ajaxListPost(Request $request)
    {
        if ($request->ajax()) {
            $platform_id = $request->get('platform_id') ?? null;
            $search = $request->get('search') ?? null;
            $status = $request->get('status') ?? 'all';
            $period = $request->get('period') ?? 'all';
            $params = [
                'platform_id' => $platform_id,
                'search' => $search,
                'status' => $status,
                'period' => $period,
            ];

            Log::info('AJAX list post requested', ['params' => $params]);

            $blog_posts = $this->service->getAllPosts($params);

            $total_post = $this->service->countTotalPost($params);

            $view = view('partials.ajax.list_post', compact('blog_posts'))->render();

            return response()->json(['html' => $view, 'total_post' => $total_post]);
        }
    }

    public function ajaxListStatus(Request $request)
    {
        if ($request->ajax()) {
            $statuses = $this->service->getAllStatus();
            $view = view('partials.ajax.list_status', compact('statuses'))->render();
            return response()->json(['html' => $view]);
        }
    }

    public function ajaxListPeriod(Request $request)
    {
        if ($request->ajax()) {
            $periods = $this->service->getAllPeriod();
            $view = view('partials.ajax.list_period', compact('periods'))->render();
            return response()->json(['html' => $view]);
        }
    }

    public function create(Request $request)
    {
        $platform_id = $request->get('platform_id') ?? null;
        $data = [
            'platform_id' => $platform_id,
            'status' => BlogPost::STATUS_PENDING,
            'user_id' => Auth::id() ?? 1,
        ];
        Log::info('Creating post', ['data' => $data]);
        //create post auto save then redirect to edit post
        $post = $this->service->createPost($data);
        return redirect()->route('post.edit', ['id' => $post->id]);
    }

    public function edit($id)
    {
        Log::info('Editing post', ['post_id' => $id]);
        $post = $this->service->getPostById($id);
        $view = "blog_posts.init_post_setting";

        if ($post->status == BlogPost::STATUS_GENERATED) {
            $view = "blog_posts.result";
        }

        return view($view, compact('post'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        Log::info('Updating post', ['post_id' => $id, 'data' => $data]);
        $post = $this->service->updatePost($id, $data);
        return redirect()->route('posts.edit', ['id' => $post->id]);
    }

    public function ajaxDelete(Request $request, $id)
    {
        if ($request->ajax()) {
            Log::info('AJAX delete post requested', ['post_id' => $id]);
            $this->service->deletePost($id);
            return response()->json(['success' => true]);
        }
    }

    public function ajaxDeleteMulti(Request $request)
    {
        if ($request->ajax()) {
            $ids = $request->get('ids');
            Log::info('AJAX delete multi posts requested', ['post_ids' => $ids]);
            $this->service->deleteMultiPosts($ids);
            return response()->json(['success' => true]);
        }
    }

    public function ajaxPreviewPost(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->get('id');
            Log::info('AJAX preview post requested', ['post_id' => $id]);
            $post = $this->service->previewPost($id);
            $post->url = route('post.show', ['id' => $id]);
            return response()->json(['post' => $post]);
        }
    }

    public function duplicate($id)
    {
        Log::info('Duplicating post', ['post_id' => $id]);
        $post = $this->service->duplicatePost($id);
        return redirect()->route('post.dashboard');
    }

    public function ajaxGeneratePost(Request $request)
    {
        if ($request->ajax()) {
            // $params = [
            //     'short_description' => $request->get('short_description') ,
            //     'post_style' => $request->get('post_style') ,
            //     'max_characters' => $request->get('max_characters'),
            //     'section_number' => $request->get('section_number') ,
            //     'keywords' => $request->get('keywords') ,
            // ];

            $params = [
                'short_description' => "APEXの競技シーンとヴァロラントの競技シーンの比較",
                'post_style' => "同じプロゲーマーとしての目線",
                'section_number' => "4",
                'keywords' => "Laz選手とImperial hal選手",
            ];

            $post_id = $request->get('post_id');
            $this->service->createPostParams(['blog_post_id' => $post_id] + $params);

            Log::info('AJAX generate post requested', ['params' => $params]);
            $this->service->updatePost($post_id, ['status' => BlogPost::STATUS_GENERATING]);
            // Dispatch the job with the parameters
            GeneratePostJob::dispatch($post_id, $params);

            return response()->json(['message' => 'Post generation job dispatched']);
        }
    }

    public function ajaxCheckPostStatus(Request $request)
    {
        if ($request->ajax()) {

            $post_id = $request->get('post_id');

            $status = $this->service->getPostStatus($post_id);

            return response()->json(['status' => $status]);
        }
    }

    public function result($id)
    {
        Log::info('Showing post result', ['post_id' => $id]);
        $post = $this->service->getPostById($id);
        $auth_user = AuthUser::user();
        $writer_article = $auth_user ? $auth_user->writer_article : 1;
        $tags = Cache::remember($writer_article, now()->addHours(config('constant.cache_remember_hour')), function() use ($writer_article) {
            return $this->api_service->getTags($writer_article);
        });
        return view('blog_posts.result', compact('post', 'tags'));
    }

    public function ajaxGenerateBlogTitle(Request $request)
    {
        if ($request->ajax()) {
            set_time_limit(0);
            $post_params = $this->service->getPostParams($request->get('post_id'));
            $res = $this->ai_service->generateTitleOutline($post_params->short_description, $post_params->keywords, $post_params->post_style, $post_params->section_number);
            return response()->json(data: ['title' => $res['title']]);
        }
    }

    public function ajaxGenerateBlogOutline(Request $request)
    {
        if ($request->ajax()) {
            set_time_limit(0);

            $post_params = $this->service->getPostParams($request->get('post_id'));
            $res = $this->ai_service->generateTitleOutline($post_params->short_description, $post_params->keywords, $post_params->post_style, $post_params->section_number);
            return response()->json(data: ['outline' => $res['outline']]);
        }
    }

    public function ajaxGenerateBlogContent(Request $request)
    {
        if ($request->ajax()) {
            set_time_limit(0);
            $title = $request->get('title') ?? '';
            $outline = $request->get('outline') ?? '';
            $post_id = $request->get('post_id');
            $tags = $request->get('tags') ?? '';

            $post_params = $this->service->getPostParams($request->get('post_id'));
            $keywords = $post_params->keywords;
            if (!empty($tags)) {
                $keywords .= ',' . implode(',', $tags);
            }

            $this->ai_service->generateBlogContent($request->get('post_id'), $post_params->short_description, $keywords, $post_params->post_style, $post_params->section_number, $title, $outline);

            $content = $this->service->getPostContent($post_id);

            return response()->json(data: ['content' => $content]);
        }
    }

    public function ajaxUpdateBlogPost(Request $request)
    {
        if ($request->ajax()) {
            $post_id = $request->get('post_id');
            $data = $request->all();
            Log::info('AJAX update blog post requested', ['post_id' => $post_id, 'data' => $data]);

            if (!empty($data['source_from']) && $data['source_from'] == 'seo_setting') {
                $seo_setting_data = [
                    'meta_title' => $data['meta_title'] ?? '',
                    'meta_description' => $data['meta_description'] ?? '',
                    'meta_keywords' => $data['meta_keywords'] ?? '',
                ];
                $this->service->updateSEOSetting($post_id, $seo_setting_data);
            } else {
                $post_params = $this->service->getPostParams($post_id);
                $tags = $data['tags'] ?? [];
                $keywords = $post_params->keywords;

                if (!empty($tags)) {
                    $keywords .= ',' . implode(',', $tags);
                }

                $data['meta_keywords'] = $keywords;
                $data['meta_description'] = $this->getMetaDescription($post_id);
                $this->service->updateSEOSetting($post_id, $data);
            }

            if (isset($data['source_from']))
                unset($data['source_from']);
            if (isset($data['meta_title']))
                unset($data['meta_title']);
            if (isset($data['meta_description']))
                unset($data['meta_description']);
            if (isset($data['meta_keywords']))
                unset($data['meta_keywords']);

            $this->service->updatePost($post_id, $data);


            return response()->json(['message' => 'Post updated', 'status' => 'success']);
        }
    }

    public function postSetting($id)
    {
        Log::info('Showing post setting', ['post_id' => $id]);
        $post = $this->service->getPostById($id);
        $post->url = route('post.show', ['id' => $id]);

        $seo_setting = $this->service->getBLogSEOSetting($id);

        return view('blog_posts.post_setting', compact('post', 'seo_setting'));
    }


    public function uploadImage(Request $request)
    {
        $image = $request->file('file');
        $path = $image->store('images', 'public');

        $media = [
            'blog_post_id' => $request->get('post_id'),
            'file_name' => $image->getClientOriginalName(),
            'file_url' => $path,
            'file_type' => $image->getClientMimeType(),
        ];

        $this->service->createMedia($media);

        return response()->json(['location' => asset('storage/' . $path)]);
    }

    private function getMetaDescription($post_id)
    {
        $meta_description = $this->ai_service->generateMetaDescription($post_id);
        return $meta_description;
    }

    public function ajaxUpdateTag(Request $request)
    {
        if ($request->ajax()) {
            $post_id = $request->get('post_id');
            $tag = $request->get('tag');
            Log::info('AJAX update tag requested', ['post_id' => $post_id, 'tag' => $tag]);
            $this->service->updateTag($post_id, $tag);
            return response()->json(['message' => 'Tags updated', 'status' => 'success']);
        }
    }

    public function ajaxRenderImage(Request $request)
    {
        if ($request->ajax()) {
            $post_id = $request->get('post_id');

            Log::info('AJAX render image requested', ['post_id' => $post_id]);

            $post = $this->service->getPostById($post_id);
            if ($post) {
                $result = $this->api_service->renderImage($post->content);

                if ($result) {

                    $url = asset($result['file_path']);
                    $media = [
                        'blog_post_id' => $request->get('post_id'),
                        'file_name' => $result['file_name'],
                        'file_url' => $url,
                        'file_type' => $result['file_type'],
                    ];

                    $this->service->createMedia($media);
                    return response()->json(['status' => 'success', 'file_url' => $url]);
                }
            }

            return response()->json(['message' => 'Tags updated', 'status' => 'fail']);
        }
    }
}
