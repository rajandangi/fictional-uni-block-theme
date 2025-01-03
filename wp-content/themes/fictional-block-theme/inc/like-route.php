<?php
add_action('rest_api_init', 'universityLikeRoutes');
function universityLikeRoutes()
{
    register_rest_route(
        'university/v1',
        'manageLike',
        array(
            'methods' => 'POST',
            'callback' => 'createLike',
            'permission_callback' => function () {
                return is_user_logged_in();
            }
        )
    );
    register_rest_route(
        'university/v1',
        'manageLike',
        array(
            'methods' => 'DELETE',
            'callback' => 'deleteLike',
            'permission_callback' => function () {
                return is_user_logged_in();
            }
        )
    );
}
function createLike($data)
{
    if (!is_user_logged_in()) {
        die("Only logged in users can create a like.");
    }

    $professor = sanitize_text_field($data['professorId']);

    $existQuery = new WP_Query(
        array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => $professor
                )
            ),
        )
    );

    if ($existQuery->found_posts != 0 or get_post_type($professor) != 'professor') {
        die("Invalid professor id.");
    }

    // Finally, create the like
    return wp_insert_post(
        array(
            'post_type' => 'like',
            'post_status' => 'publish',
            'post_title' => esc_attr(get_the_title($professor)) . '-' . esc_attr(get_current_user_id()),
            'meta_input' => array(
                'liked_professor_id' => $professor
            )
        )
    );
}
function deleteLike($data)
{
    $likeId = sanitize_text_field($data['like']);
    if (get_current_user_id() == get_post_field('post_author', $likeId) and get_post_type($likeId) == 'like') {
        wp_delete_post($likeId, true); // true means force delete the post and bypass trash
        return 'Congrats, like deleted.';
    } else {
        die("You do not have permission to delete that.");
    }
}
