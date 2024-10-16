<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Schema::create('blog_posts', function (Blueprint $table) {
        //     $table->id();
        //     $table->uuid('uuid')->unique();
        //     $table->text('title');
        //     $table->text('content');
        //     $table->text('short_content');
        //     $table->string('status')->default('draft');
        //     $table->unsignedBigInteger('user_id');
        //     $table->timestamp('published_at')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        //make 20 blog posts
        for ($i = 0; $i < 20; $i++) {
            $title = 'Blog Post ' . $i;
            $content = 'This is the content of blog post ' . $i;
            $shortContent = 'This is the short content of blog post ' . $i;
            $status = 'published';
            $userId = 1;
            $publishedAt = now();
            $uuid = \Str::uuid();

            \DB::table('blog_posts')->insert([
                'uuid' => $uuid,
                'title' => $title,
                'content' => $content,
                'short_content' => $shortContent,
                'status' => $status,
                'user_id' => $userId,
                'published_at' => $publishedAt,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
