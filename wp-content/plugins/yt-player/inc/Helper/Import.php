<?php

namespace YTP\Helper;

use  YTP\Helper\Utils ;
class Import
{
    public static function meta()
    {
        $players = new \WP_Query( [
            'post_type'      => 'ytplayer',
            'posts_per_page' => -1,
        ] );
        foreach ( $players->posts as $player ) {
            if ( get_post_meta( $player->ID, '_ytp', true ) ) {
                break;
            }
            $oldMeta = self::getOldMeta( $player->ID );
            $data = [
                'source'             => $oldMeta['source'],
                'width'              => ( in_array( $oldMeta['width'], [
                '',
                '0',
                ' ',
                0
            ] ) ? [
                'width' => 100,
                'unit'  => '%',
            ] : [
                'width' => $oldMeta['width'],
                'unit'  => 'px',
            ] ),
                'controls'           => self::getControls( $oldMeta ),
                'autoplay'           => $oldMeta['autoplay'],
                'muted'              => '0',
                'seekTime'           => $oldMeta['seekTime'],
                'startTime'          => $oldMeta['startTime'],
                'disableContextMenu' => $oldMeta['disableContextMenu'],
                'hideControls'       => $oldMeta['hideControls'],
                'clickToPlay'        => $oldMeta['clickToPlay'],
                'hideYoutubeUI'      => '0',
            ];
            update_post_meta( $player->ID, '_ytp', $data );
        }
    }
    
    public static function option()
    {
    }
    
    public static function getOldMeta( $id )
    {
        $data = [
            'source'             => self::getMeta( $id, '_ytp_video_id', '0' ),
            'width'              => self::getMeta( $id, '_ytp_video_width', '0' ),
            'controls'           => [
            'play-large' => self::getMeta( $id, '_ytp_large_play', '0' ),
            'play'       => self::getMeta( $id, '_ytp_play', 'off' ),
            'progress'   => self::getMeta( $id, '_ytp_progress_bar', 'off' ),
            'duration'   => self::getMeta( $id, '_ytp_duration', 'off' ),
            'mute'       => self::getMeta( $id, '_ytp_mute_button', 'off' ),
            'volume'     => self::getMeta( $id, '_ytp_volume_control', 'off' ),
            'settings'   => self::getMeta( $id, '_ytp_setting', 'off' ),
            'fullscreen' => self::getMeta( $id, '_ytp_video_fs', 'off' ),
        ],
            'autoplay'           => self::getMeta( $id, '_ytp_autoplay', 'off' ),
            'clickToPlay'        => self::getMeta( $id, '_ytp_click2play', 'off' ),
            'disableContextMenu' => self::getMeta( $id, '_ytp_disableContextMenu', 'off' ),
            'seekTime'           => self::getMeta( $id, '_ytp_seektime', 'off' ),
            'startTime'          => self::getMeta( $id, '_ytp_start_time', 'off' ),
            'hideControls'       => self::getMeta( $id, '_ytp_hide_control', 'off' ),
        ];
        return $data;
    }
    
    public static function getOldOptions()
    {
    }
    
    public static function getControls( $meta )
    {
        $controls = [];
        foreach ( $meta['controls'] as $key => $control ) {
            if ( $control !== 'on' ) {
                $controls[] = $key;
            }
        }
        return $controls;
        return wp_parse_args( [ 'pipe', 'airplay' ], $controls );
    }
    
    public static function getMeta( $id, $meta, $default = false )
    {
        $meta = get_post_meta( $id, $meta, true );
        return $meta;
    }

}