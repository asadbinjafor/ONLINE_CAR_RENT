<?php
class BlogController
{
    private BlogModel $blogs;

    public function __construct()
    {
        $this->blogs = new BlogModel();
    }

    public function index(): void
    {
        view('blog/index', [
            'title' => 'Rental Experiences',
            'posts' => $this->blogs->all(),
        ]);
    }
}
