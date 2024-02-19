<?php
/**
 * Plugin Name:     Ultimate Member - Ratings in Members Directory
 * Description:     Extension to Ultimate Member for adding Ratings to the Members Directory Page.
 * Version:         1.5.0
 * Requires PHP:    7.4
 * Author:          Miss Veronica
 * License:         GPL v3 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Author URI:      https://github.com/MissVeronica
 * Text Domain:     ultimate-member
 * Domain Path:     /languages
 * UM version:      2.8.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; 
if ( ! class_exists( 'UM' ) ) return;

class UM_Ratings_Members_Directory {

    function __construct() {

        add_filter( 'um_settings_structure',    array( $this, 'um_settings_structure_ratings' ), 20, 1 );
        add_filter( 'um_ajax_get_members_data', array( $this, 'um_ajax_get_members_data_ratings' ), 10, 3 );
    }

    public function um_ajax_get_members_data_ratings( $data_array, $user_id, $directory_data ) {

        global $current_user;
 
        $data_array['ratings'] = '';
        $data_array['ratings_line'] = '';

        $form_ids = UM()->options()->get( 'um_ratings_members_directory_form_ids' );
        if ( ! empty( $form_ids )) {
            $form_ids = array_map( 'trim', array_map( 'sanitize_text_field', explode( ',', $form_ids )));
        }

        if ( empty( $form_ids ) || in_array( $directory_data['form_id'], $form_ids )) {

            $roles = UM()->options()->get( 'um_ratings_members_directory_roles' );

            if ( is_array( $roles )) {
                $roles = array_map( 'sanitize_text_field', $roles );
                $prio_role =  UM()->roles()->get_priority_user_role( $current_user->ID );
            }

            if ( empty( $roles ) || in_array( $prio_role, $roles )) {

                $rating_keys = array_map( 'trim', array_map( 'sanitize_text_field', explode( ',', UM()->options()->get( 'um_ratings_members_directory' ))));
                $list_order = array();
                $list_title = array();

                foreach( $rating_keys as $rating_key ) {

                    $stars = um_user( $rating_key );
                    //$stars = get_user_meta( $user_id, $rating_key, true );
                    if ( ! empty( $stars )) {

                        $rating_field = UM()->builtin()->get_a_field( $rating_key );

                        if ( ! empty( $rating_field ) && $rating_field['type'] == 'rating' ) {
                            //if ( $rating_field['public'] == 1 || ( $rating_field['public'] == 2 && is_user_logged_in())) {
                            if ( is_user_logged_in()) {

                                $list_order[$rating_key] = $stars;
                                $list_title[$rating_key] = isset( $rating_field['title'] ) ? $rating_field['title'] : $rating_field['label'];
                            }
                        }
                    }
                }

                $sorting = trim( sanitize_text_field( UM()->options()->get( 'um_ratings_members_directory_sorting' )));

                if ( $sorting == 'descending' ) arsort( $list_order );
                if ( $sorting == 'ascending' ) asort( $list_order );

                foreach( $list_order as $key => $stars ) {

                    $data_array['ratings'] .= '<div>' . $list_title[$key] . '</div><div class="um-rating-members">';
                    $data_array['ratings_line'] .= '<div>' . $list_title[$key] . '&nbsp;';
                    for ( $i = 1; $i <= $stars; $i++ ) { 
                        $data_array['ratings'] .= '<i class="star-on-png" title="' . $stars . '"></i>';
                        $data_array['ratings_line'] .= '<i class="star-on-png" title="' . $stars . '"></i>';
                    }
                    $data_array['ratings'] .= '</div>';
                    $data_array['ratings_line'] .=  '</div>';
                }
            }
        }

        return $data_array;
    }

    public function um_settings_structure_ratings( $settings_structure ) {

        if ( ! isset( $settings_structure['misc'] )) {
            $settings_structure['misc'] = array( 'title'       => __( 'Misc', 'ultimate-member' ),
                                                 'description' => __( 'Old UM Miscellaneous tab now only used for some free Plugins (UM 2.8.3)', 'ultimate-member' ));
        }

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
            'tooltip'       => __( 'Form Ids comma separated. Blank all Members Directories.', 'ultimate-member' )
        );

        $settings_structure['misc']['fields'][] = array(
            'id'            => 'um_ratings_members_directory_sorting',
            'type'          => 'select',
            'size'          => 'small',
            'options'       => array(   'nosorting'  => __( 'No sorting', 'ultimate-member' ),
                                        'ascending'  => __( 'Ascending', 'ultimate-member' ),
                                        'descending' => __( 'Descending', 'ultimate-member' ) ),
            'label'         => __( 'Ratings Members Directory - Sorting stars', 'ultimate-member' ),
            'tooltip'       => __( 'Select No sorting (meta_key order), Ascending or Descending number of stars.', 'ultimate-member' )
        );

        $settings_structure['misc']['fields'][] = array(
            'id'          => 'um_ratings_members_directory_roles',
            'type'        => 'select',
            'multi'       => true,
            'options'     => UM()->roles()->get_roles(),
            'label'       => __( 'Ratings Members Directory - Select Roles for viewing', 'ultimate-member' ),
            'tooltip'     => __( 'Leave empty if you want to display ratings for all logged in users', 'ultimate-member' ),
            'size'        => 'small',
        );

        return $settings_structure;
    }
}

new UM_Ratings_Members_Directory();
