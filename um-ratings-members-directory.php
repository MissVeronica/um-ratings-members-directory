<?php
/**
 * Plugin Name:     Ultimate Member - Ratings in Members Directory
 * Description:     Extension to Ultimate Member for adding Ratings to the Members Directory Page.
 * Version:         1.0.0
 * Requires PHP:    7.4
 * Author:          Miss Veronica
 * License:         GPL v3 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Author URI:      https://github.com/MissVeronica
 * Text Domain:     ultimate-member
 * Domain Path:     /languages
 * UM version:      2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; 
if ( ! class_exists( 'UM' ) ) return;

class UM_Ratings_Members_Directory {

    function __construct() {

        add_filter( 'um_settings_structure',    array( $this, 'um_settings_structure_ratings' ), 10, 1 );
        add_filter( 'um_ajax_get_members_data', array( $this, 'um_ajax_get_members_data_ratings' ), 10, 3 );
    }

    public function um_ajax_get_members_data_ratings( $data_array, $user_id, $directory_data ) {

        $data_array['ratings'] = '';

        $form_ids = UM()->options()->get( 'um_ratings_members_directory_form_ids' );
        if ( ! empty( $form_ids )) {
            $form_ids = array_map( 'trim', array_map( 'sanitize_text_field', explode( ',', $form_ids )));
        }

        if ( empty( $form_ids ) || in_array( $directory_data['form_id'], $form_ids )) {

            $rating_keys = array_map( 'trim', array_map( 'sanitize_text_field', explode( ',', UM()->options()->get( 'um_ratings_members_directory' ))));           

            foreach( $rating_keys as $rating_key ) {

                $stars = um_user( $rating_key );
                if ( ! empty( $stars )) {

                    $rating_field = UM()->builtin()->get_a_field( $rating_key );

                    if ( ! empty( $rating_field ) && $rating_field['type'] == 'rating' ) {
                        if ( $rating_field['public'] == 1 || ( $rating_field['public'] == 2 && is_user_logged_in())) {

                            $title = isset( $rating_field['title'] ) ? $rating_field['title'] : $rating_field['label'];
                            $data_array['ratings'] .= '<div>' . $title . '</div><div class="um-rating-members">';
                            for ( $i = 1; $i <= $stars; $i++ ) { 
                                $data_array['ratings'] .= '<i class="star-on-png" title="' . $stars . '"></i>';
                            }
                            $data_array['ratings'] .= '</div>';
                        }
                    }
                }
            }
        }

        return $data_array;
    }

    public function um_settings_structure_ratings( $settings_structure ) {

        $settings_structure['misc']['fields'][] = array(
            'id'            => 'um_ratings_members_directory',
            'type'          => 'text',
            'label'         => __( 'Ratings Members Directory - Meta Keys', 'ultimate-member' ),
            'tooltip'       => __( 'Name of the rating meta keys comma separated.', 'ultimate-member' )
            );
            
        $settings_structure['misc']['fields'][] = array(
            'id'            => 'um_ratings_members_directory_form_ids',
            'type'          => 'text',
            'label'         => __( 'Ratings Members Directory - Form Ids', 'ultimate-member' ),
            'tooltip'       => __( 'Form Ids comma separated.', 'ultimate-member' )
            );

        return $settings_structure;
    }
}

new UM_Ratings_Members_Directory();
