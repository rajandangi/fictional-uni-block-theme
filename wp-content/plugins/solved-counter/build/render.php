<?php

/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */


wp_interactivity_state('create-block', ['solvedCount' => 0]);
?>

<!-- data-wp-interactive is a must for the block to work with state -->
<div class="solved-counter" data-wp-interactive="create-block">
    <p>Questions Solved: <strong><span data-wp-text="state.solvedCount"></span></strong></p>
</div>

<style>
    .solved-counter {
        margin: 1rem 0;
        background: #EBEBEB;
        padding: 1rem;
        border-radius: 5px;
    }
</style>