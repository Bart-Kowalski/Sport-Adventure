<?php


namespace Wpcb2\Actions;


class GetConditionData
{
    public function execute()
    {
        $arr = [];

        $arr['pages'][] = [
            'value' => '-1',
            'label' => 'Home Page'
        ];

        $arr['posts'] = [];

        $pages = get_posts([
            'numberposts' => -1,
            'post_type' => 'page',
        ]);
        $posts = get_posts([
            'numberposts' => -1,
            'post_type' => 'post',
        ]);

        foreach ($pages as $page) {
            $arr['pages'][] = [
                'value' => $page->ID,
                'label' => $page->post_title
            ];
        }

        foreach ($posts as $post) {
            $arr['posts'][] = [
                'value' => $post->ID,
                'label' => $post->post_title
            ];
        }

        $response = [[
            "label" => "pages",
            "options" => $arr['pages']
        ], [
            "label" => "posts",
            "options" => $arr['posts']
        ]];

        echo json_encode($response);
        die;
    }
}
