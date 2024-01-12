<?php
class WordCountAndTimeContentFilter
{
    function __construct()
    {
        add_filter('the_content', array($this, 'ifWrap'));
    }

    function ifWrap($content)
    {
        if (
            is_main_query() and is_single() and
            (
                get_option('wcp_wordcount', 1) or
                get_option('wcp_charactercount', 1) or
                get_option('wcp_readtime', 1))
        ) {
            return $this->createHTML($content);
        }
        return $content;
    }

    function createHTML($content)
    {
        $html = '<h3>' . esc_html(get_option('wcp_headline', esc_html__('Post Statistics', 'wcdomain'))) . '</h3><p>';
        // get word count once because both wordcount and read time will need it
        if (get_option('wcp_wordcount', '1') or get_option('wcp_readtime', '1')) {
            // strip_tags removes html tags from content before counting words
            $wordCount = str_word_count(strip_tags($content));
        }
        if (get_option('wcp_wordcount', '1')) {
            $html .= esc_html__('Words:', 'wcdomain') . ' ' . $wordCount . '<br>';
        }
        if (get_option('wcp_charactercount', '1')) {
            $html .= esc_html__('Characters:', 'wcdomain') . '' . strlen(strip_tags($content)) . '<br>';
        }

        if (get_option('wcp_readtime', '1')) {
            // 225 is the average reading speed in words per minute (wpm) for adults according to Wikipedia
            $html .= esc_html__('Time to read:', 'wcdomain') . '' . round($wordCount / 225) . ' minute(s)<br>';
        }

        $html .= '</p>';

        if (get_option('wcp_location', 0)) {
            return $content . $html;
        }
        return $html . $content;
    }
}
$wordCountAndTimeContentFilter = new WordCountAndTimeContentFilter();
