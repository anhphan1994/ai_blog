<?php

namespace App\Http\Controllers;

use App\Helpers\AIHelper;
use App\Services\AIService;
use Cache;
use Illuminate\Http\Request;
use Log;

class AIController extends Controller
{
    protected $service;

    public function __construct(AIService $service)
    {
        $this->service = $service;
    }


    // public function testCreatePost()
    // {
    //     set_time_limit(0);
    //     $short_desc = "APEXの競技シーンとヴァロラントの競技シーンの比較";
    //     $keyword = "Laz選手とImperial hal選手";
    //     $style = "同じプロゲーマーとしての目線";
    //     $num_of_section = "3";

    //     $first_prompt = "
    //         #あなたの役割
    //         読者100万人を超える超人気SEOブロガー。

    //         #あなたの目的
    //         ブログのタイトルとアウトラインだけを作成する。

    //         #あなたのスキル
    //         ブログのアウトラインを作成するために必要なライティングスキルとコンテンツ企画の基礎知識を持つ。

    //         #依頼者条件
    //         ブログ記事の企画を効率よく整理し、明瞭な記事構造を設計したいブロガー。


    //         #実行命令
    //         ブログのトピック（書きたいこと）は「" . $short_desc . "」です。
    //         そして今回のトピックで必ず含めたいキーワードは「" . $keyword . "」です。

    //         上記のテーマとキーワードを参考にして、
    //         ブログのアウトラインを作成してください。

    //         アウトラインは指定された数で必ず作って下さい。

    //         各アウトラインには専門的な用語を避けてください。

    //         #生成条件について
    //         ・指定されトピックに沿った内容のブログ記事を生成する
    //         ・タイトルはSEO最適化の原則、キーワードリサーチ、および競合他社の分析情報を参照する。
    //         ・読者の関心を惹く魅力的なタイトルとアウトラインを提案する


    //         #筆者のトーン
    //         「" . $style . "」です。
    //         ターゲットが興味をそそるような、魅力的なものにしてください。

    //         #指定アウトライン数
    //         「" . $num_of_section . "」

    //         #補足:
    //         - 指示の復唱はしないてください。
    //         - 余計な前置き、結論やまとめは書かないください。

    //         #出力形式

    //         《ブログタイトル》(見出しは必ず《》で括って）
    //         見出し1:『見出し1』(見出しは必ず『』で括って）
    //         見出し2:『見出し2』(見出しは必ず『』で括って）
    //     ... ";

    //     if (Cache::has('first_response')) {
    //         $first_response = Cache::get('first_response');
    //     } else {
    //         $first_response = AIHelper::sendMessageToAI($first_prompt);
    //         Cache::put('first_response', $first_response, now()->addHours(10));
    //     }


    //     $title = explode("\n", $first_response)[0];

    //     $sections = explode("\n", $first_response);
    //     $outline_arr = array_slice($sections, 1);

    //     $outline_list = "";
    //     foreach ($outline_arr as $section) {
    //         $outline_list .= $section . "\n";
    //     }

    //     $second_prompt = "
    //         #あなたの役割
    //         SEOの原則に基づいて効果的なブログタイトル、本文を作成するプロフェッショナルです。私の目的は、検索エンジンでの上位表示を狙い、サイトのトラフィックとエンゲージメントを増加させるための魅力的なブログ記事を作成することです。

    //         #あなたの目的
    //         アウトラインに沿ったブログ記事の作成

    //         #あなたのスキル
    //         日本最高峰の高いライティングスキルとアウトラインからブログ記事を構成するライター兼ブロガー。
    //         #リソースに沿りながらオリジナリティのある魅力的なブログを書くことに定評がある。

    //         #依頼者条件
    //         アウトラインに従って質の高いブログ記事を書きたいと考えている人気ブロガー。

    //         #リソース
    //         ブログタイトル:（" . $title . "）
    //         トピックの内容:「" . $short_desc . "」
    //         今回のブログで必ず入れたいキーワード:「" . $keyword . "」

    //         #明瞭化の要件
    //         1. アウトラインの各ポイントを詳細に理解する。
    //         2. アウトラインに示された順序で情報とアイデアを整理する。
    //         3. 初稿を完成させ、必要に応じて編集や校閲を行う。
    //         4. 読者が興味を持つ可能性が高い要素を取り入れる。
    //         5. 完成した記事をアウトラインと照らし合わせて、要点が網羅されているか確認する。

    //         #実行命令
    //         まず、ブログのアウトラインである下記を確認し本文の全体図を理解してください。
    //         " . $outline_list . "

    //         その後、（見出し1）の部分の本文を、
    //         「#文章作成のガイドライン」にしたがって書き出してください。

    //         #筆者のトーン
    //         「" . $style . "」

    //         #文章作成のガイドライン
    //         - 専門的な用語は避け、「#筆者のトーン」でライティングを行って下さい。
    //         - 「見出し1」生成時のみブログの書き出しとしての挨拶を行ってください
    //         -一番最後の「見出し」ではブログの締めの挨拶を行ってください。
    //         -一番最後の「見出し」以外はブログは同じページで続くので締めないでください。

    //         #補足:
    //         - 指示の復唱はしないてください。
    //         - 余計な前置き、結論やまとめは書かないください。
    //         - 他のアウトラインは別で生成を行うので、他のアウトラインと内容が重複しそうな場合は今回の生成ではその内容については触れないでください。

    //         #出力形式
    //         -『見出し』(必ず『』で括って)
    //         -（見出しの内容）
    //         ";


    //     if (Cache::has('second_response')) {
    //         $second_response = Cache::get('second_response');
    //     } else {
    //         $second_response = AIHelper::sendMessageToAI($second_prompt);
    //         Cache::put('second_response', $second_response, now()->addHours(10));
    //     }




    //     foreach ($outline_arr as $index => $section) {

    //         $old_response = $index == 0 ? $second_response : $new_response;
    //         $main_prompt = "
    //         SEOの原則に基づいて効果的なブログタイトル、本文を作成するプロフェッショナルです。私の目的は、検索エンジンでの上位表示を狙い、サイトのトラフィックとエンゲージメントを増加させるための魅力的なブログ記事を作成することです。

    //         #あなたの目的
    //         アウトラインに沿ったブログ記事の作成

    //         #あなたのスキル
    //         日本最高峰の高いライティングスキルとアウトラインからブログ記事を構成するライター兼ブロガー。
    //         #リソースに沿りながらオリジナリティのある魅力的なブログを書くことに定評がある。

    //         #依頼者条件
    //         アウトラインに従って質の高いブログ記事を書きたいと考えている人気ブロガー。

    //         #リソース
    //         ブログタイトル:（" . $title . "）
    //         トピックの内容:「" . $short_desc . "」
    //         今回のブログで必ず入れたいキーワード:「" . $keyword . "」

    //         #明瞭化の要件
    //         1. アウトラインの各ポイントを詳細に理解する。
    //         2. アウトラインに示された順序で情報とアイデアを整理する。
    //         3. 初稿を完成させ、必要に応じて編集や校閲を行う。
    //         4. 読者が興味を持つ可能性が高い要素を取り入れる。
    //         5. 完成した記事をアウトラインと照らし合わせて、要点が網羅されているか確認する。

    //         #実行命令
    //         まず、ブログのアウトラインである下記を確認し本文の全体図を理解してください。
    //         " . $outline_list . "
    //         その後、これまで完成している見出しの次の見出しと本文を、
    //         「#文章作成のガイドライン」にしたがって書き出してください。



    //         #筆者のトーン
    //         「" . $short_desc . "」

    //         #文章作成のガイドライン
    //         - 専門的な用語は避け、「#筆者のトーン」でライティングを行って下さい。
    //         - 「見出し1」生成時のみブログの書き出しとしての挨拶を行ってください
    //         -一番最後の「見出し」ではブログの締めの挨拶を行ってください。
    //         -一番最後の「見出し」以外はブログは同じページで続くので締めないでください。


    //         #補足:
    //         - 指示の復唱はしないてください。
    //         - 余計な前置き、結論やまとめは書かないください。
    //         - 他のアウトラインは別で生成を行うので、他のアウトラインと内容が重複しそうな場合は今回の生成ではその内容については触れないでください。
    //         - あなたが今回の「見出し」で過去に生成した内容はコチラです。過去に生成した内容と同じことを繰り返さないようにライティングを行って下さい。
    //         [" . $old_response . "]

    //         #出力形式
    //         -『見出し』(必ず『』で括って)
    //         -（見出しの内容）                                                
    //     ";



    //         $res = AIHelper::sendMessageToAI($main_prompt);
    //         if (!empty($res)) {
    //             $new_response = $old_response . "\n\n" . $res;
    //         } else {
    //             $new_response = $old_response;
    //         }

    //         Log::info('AI response: ' . $new_response);
    //     }


    //     dd(vars: $new_response);

    // }


    public function generateBlogContent(Request $request)
    {
        set_time_limit(0);
        $time_start = microtime(true);
        $short_desc_test = "APEXの競技シーンとヴァロラントの競技シーンの比較";
        $keyword_test = "Laz選手とImperial hal選手";
        $style_test = "同じプロゲーマーとしての目線";
        $num_of_section_test = "7";

        $short_desc = $request->short_desc ?? $short_desc_test;
        $keyword = $request->keyword ?? $keyword_test;
        $style = $request->style ?? $style_test;
        $num_of_section = $request->num_of_section ?? $num_of_section_test;

        if (empty($short_desc) || empty($keyword) || empty($style) || empty($num_of_section)) {
            return response()->json(data: ['error' => 'Please provide all the required fields']);
        }

        $content = $this->service->generateBlogContent($short_desc, $keyword, $style, $num_of_section);
        $time_end = microtime(true);
        return response()->json(data: ['content' => $content, 'time' => $time_end - $time_start]);
    }

    public function generateBlogTitle(Request $request)
    {
        set_time_limit(0);

        $short_desc_test = "APEXの競技シーンとヴァロラントの競技シーンの比較";
        $keyword_test = "Laz選手とImperial hal選手";
        $style_test = "同じプロゲーマーとしての目線";
        $num_of_section_test = "7";

        $short_desc = $request->short_desc ?? $short_desc_test;
        $keyword = $request->keyword ?? $keyword_test;
        $style = $request->style ?? $style_test;
        $num_of_section = $request->num_of_section ?? $num_of_section_test;

        if (empty($short_desc) || empty($keyword) || empty($style) || empty($num_of_section)) {
            return response()->json(data: ['error' => 'Please provide all the required fields']);
        }


       $title = $this->service->generateBlogTitle($short_desc, $keyword, $style, $num_of_section);

        return response()->json(data: ['title' => $title]);
    }

    public function generateBlogOutline(Request $request)
    {
        set_time_limit(0);

        $short_desc_test = "APEXの競技シーンとヴァロラントの競技シーンの比較";
        $keyword_test = "Laz選手とImperial hal選手";
        $style_test = "同じプロゲーマーとしての目線";
        $num_of_section_test = "7";

        $short_desc = $request->short_desc ?? $short_desc_test;
        $keyword = $request->keyword ?? $keyword_test;
        $style = $request->style ?? $style_test;
        $num_of_section = $request->num_of_section ?? $num_of_section_test;

        if (empty($short_desc) || empty($keyword) || empty($style) || empty($num_of_section)) {
            return response()->json(data: ['error' => 'Please provide all the required fields']);
        }

        $outline = $this->service->generateBlogOutline($short_desc, $keyword, $style, $num_of_section);

        return response()->json(data: ['outline' => $outline]);
    }
}
