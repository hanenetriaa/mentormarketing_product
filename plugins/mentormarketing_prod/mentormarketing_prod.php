<?php

/**
 * Plugin Name: Mentor Marketing Prod
 * Description: 
 * Author: hanene triaa
 * Version: 1.0.0
 * Text Domain: mentormarketing_prod
 * Domain Path: /languages
 * License: GPL-2.0+
 */


function register_my_cpts()
{

    /**
     * Post Type: mentormarketing_prod.
     */

    $labels = [
        "name" => __("mentormarketing_prod", "mentormarketing_prod"),
        "singular_name" => __("mentormarketing_prod", "mentormarketing_prod"),
        "menu_name" => __("Mon mentormarketing_prod", "mentormarketing_prod"),
        "all_items" => __("Tous les mentormarketing_prod", "mentormarketing_prod"),
        "add_new" => __("Ajouter un nouveau", "mentormarketing_prod"),
        "add_new_item" => __("Ajouter un nouveau mentormarketing_prod", "mentormarketing_prod"),
        "edit_item" => __("Modifier mentormarketing_prod", "mentormarketing_prod"),
        "new_item" => __("Nouveau mentormarketing_prod", "mentormarketing_prod"),
        "view_item" => __("Voir mentormarketing_prod", "mentormarketing_prod"),
        "view_items" => __("Voir mentormarketing_prod", "mentormarketing_prod"),
        "search_items" => __("Recherche de mentormarketing_prod", "mentormarketing_prod"),
        "not_found" => __("Aucun mentormarketing_prod trouvé", "mentormarketing_prod"),
        "not_found_in_trash" => __("Aucun mentormarketing_prod trouvé dans la corbeille", "mentormarketing_prod"),
        "parent" => __("mentormarketing_prod parent :", "mentormarketing_prod"),
        "featured_image" => __("Image mise en avant pour ce mentormarketing_prod", "mentormarketing_prod"),
        "set_featured_image" => __("Définir l’image mise en avant pour ce mentormarketing_prod", "mentormarketing_prod"),
        "remove_featured_image" => __("Retirer l’image mise en avant pour ce mentormarketing_prod", "mentormarketing_prod"),
        "use_featured_image" => __("Utiliser comme image mise en avant pour ce mentormarketing_prod", "mentormarketing_prod"),
        "archives" => __("Archives de mentormarketing_prod", "mentormarketing_prod"),
        "insert_into_item" => __("Insérer dans mentormarketing_prod", "mentormarketing_prod"),
        "uploaded_to_this_item" => __("Téléverser sur ce mentormarketing_prod", "mentormarketing_prod"),
        "filter_items_list" => __("Filtrer la liste de mentormarketing_prod", "mentormarketing_prod"),
        "items_list_navigation" => __("Navigation de liste de mentormarketing_prod", "mentormarketing_prod"),
        "items_list" => __("Liste de mentormarketing_prod", "mentormarketing_prod"),
        "attributes" => __("Attributs de mentormarketing_prod", "mentormarketing_prod"),
        "name_admin_bar" => __("mentormarketing_prod", "mentormarketing_prod"),
        "item_published" => __("mentormarketing_prod publié", "mentormarketing_prod"),
        "item_published_privately" => __("mentormarketing_prod publié en privé.", "mentormarketing_prod"),
        "item_reverted_to_draft" => __("mentormarketing_prod repassé en brouillon.", "mentormarketing_prod"),
        "item_scheduled" => __("mentormarketing_prod planifié", "mentormarketing_prod"),
        "item_updated" => __("mentormarketing_prod mis à jour.", "mentormarketing_prod"),
        "parent_item_colon" => __("mentormarketing_prod parent :", "mentormarketing_prod"),
    ];

    $args = [
        "label" => __("mentormarketing_prod", "mentormarketing_prod"),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "rest_namespace" => "wp/v2",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "can_export" => false,
        "rewrite" => ["slug" => "mentormarketing_prod", "with_front" => true],
        "query_var" => true,
        "supports" => ["title", "editor", "thumbnail"],
        "taxonomies" => ["category", "post_tag"],
        "show_in_graphql" => false,
    ];

    register_post_type("mentormarketing_prod", $args);
}

add_action('init', 'register_my_cpts');

function register_my_taxes()
{

    /**
     * Taxonomy: Brands.
     */

    $labels = [
        "name" => __("Brands", "mentormarketing_prod"),
        "singular_name" => __("Brand", "mentormarketing_prod"),
        "menu_name" => __("Brands", "mentormarketing_prod"),
        "all_items" => __("Tous les Brands", "mentormarketing_prod"),
        "edit_item" => __("Modifier Brand", "mentormarketing_prod"),
        "view_item" => __("Voir Brand", "mentormarketing_prod"),
        "update_item" => __("Mettre à jour le nom de Brand", "mentormarketing_prod"),
        "add_new_item" => __("Ajouter un nouveau Brand", "mentormarketing_prod"),
        "new_item_name" => __("Nom du nouveau Brand", "mentormarketing_prod"),
        "parent_item" => __("Parent dBrand", "mentormarketing_prod"),
        "parent_item_colon" => __("Brand parent :", "mentormarketing_prod"),
        "search_items" => __("Recherche de Brands", "mentormarketing_prod"),
        "popular_items" => __("Brands populaires", "mentormarketing_prod"),
        "separate_items_with_commas" => __("Séparer les Brands avec des virgules", "mentormarketing_prod"),
        "add_or_remove_items" => __("Ajouter ou supprimer des Brands", "mentormarketing_prod"),
        "choose_from_most_used" => __("Choisir parmi les Brands les plus utilisés", "mentormarketing_prod"),
        "not_found" => __("Aucun Brands trouvé", "mentormarketing_prod"),
        "no_terms" => __("Aucun Brands", "mentormarketing_prod"),
        "items_list_navigation" => __("Navigation de liste de Brands", "mentormarketing_prod"),
        "items_list" => __("Liste de Brands", "mentormarketing_prod"),
        "back_to_items" => __("Retourner à Brands", "mentormarketing_prod"),
        "name_field_description" => __("Le nom tel qu’il apparaîtra sur votre site.", "mentormarketing_prod"),
        "parent_field_description" => __("Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.", "mentormarketing_prod"),
        "slug_field_description" => __("The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.", "mentormarketing_prod"),
        "desc_field_description" => __("The description is not prominent by default; however, some themes may show it.", "mentormarketing_prod"),
    ];


    $args = [
        "label" => __("Brands", "mentormarketing_prod"),
        "labels" => $labels,
        "public" => true,
        "publicly_queryable" => true,
        "hierarchical" => false,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => ['slug' => 'brand', 'with_front' => true,],
        "show_admin_column" => false,
        "show_in_rest" => true,
        "show_tagcloud" => false,
        "rest_base" => "brand",
        "rest_controller_class" => "WP_REST_Terms_Controller",
        "rest_namespace" => "wp/v2",
        "show_in_quick_edit" => false,
        "sort" => false,
        "show_in_graphql" => false,
    ];
    register_taxonomy("brand", ["mentormarketing_prod"], $args);

    /**
     * Taxonomy: Statuts.
     */

    $labels = [
        "name" => __("Statuts", "mentormarketing_prod"),
        "singular_name" => __("statut", "mentormarketing_prod"),
        "menu_name" => __("Statuts", "mentormarketing_prod"),
        "all_items" => __("Tous les Statuts", "mentormarketing_prod"),
        "edit_item" => __("Modifier statut", "mentormarketing_prod"),
        "view_item" => __("Voir statut", "mentormarketing_prod"),
        "update_item" => __("Mettre à jour le nom de statut", "mentormarketing_prod"),
        "add_new_item" => __("Ajouter un nouveau statut", "mentormarketing_prod"),
        "new_item_name" => __("Nom du nouveau statut", "mentormarketing_prod"),
        "parent_item" => __("Parent dstatut", "mentormarketing_prod"),
        "parent_item_colon" => __("statut parent :", "mentormarketing_prod"),
        "search_items" => __("Recherche de Statuts", "mentormarketing_prod"),
        "popular_items" => __("Statuts populaires", "mentormarketing_prod"),
        "separate_items_with_commas" => __("Séparer les Statuts avec des virgules", "mentormarketing_prod"),
        "add_or_remove_items" => __("Ajouter ou supprimer des Statuts", "mentormarketing_prod"),
        "choose_from_most_used" => __("Choisir parmi les Statuts les plus utilisés", "mentormarketing_prod"),
        "not_found" => __("Aucun Statuts trouvé", "mentormarketing_prod"),
        "no_terms" => __("Aucun Statuts", "mentormarketing_prod"),
        "items_list_navigation" => __("Navigation de liste de Statuts", "mentormarketing_prod"),
        "items_list" => __("Liste de Statuts", "mentormarketing_prod"),
        "back_to_items" => __("Retourner à Statuts", "mentormarketing_prod"),
        "name_field_description" => __("Le nom tel qu’il apparaîtra sur votre site.", "mentormarketing_prod"),
        "parent_field_description" => __("Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.", "mentormarketing_prod"),
        "slug_field_description" => __("The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.", "mentormarketing_prod"),
        "desc_field_description" => __("The description is not prominent by default; however, some themes may show it.", "mentormarketing_prod"),
    ];


    $args = [
        "label" => __("Statuts", "mentormarketing_prod"),
        "labels" => $labels,
        "public" => true,
        "publicly_queryable" => true,
        "hierarchical" => false,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => ['slug' => 'statuts', 'with_front' => true,],
        "show_admin_column" => false,
        "show_in_rest" => true,
        "show_tagcloud" => false,
        "rest_base" => "statuts",
        "rest_controller_class" => "WP_REST_Terms_Controller",
        "rest_namespace" => "wp/v2",
        "show_in_quick_edit" => false,
        "sort" => false,
        "show_in_graphql" => false,
    ];
    register_taxonomy("statuts", ["mentormarketing_prod"], $args);

    /**
     * Taxonomy: Names.
     */

    $labels = [
        "name" => __("Names", "mentormarketing_prod"),
        "singular_name" => __("name", "mentormarketing_prod"),
        "menu_name" => __("Names", "mentormarketing_prod"),
        "all_items" => __("Tous les Names", "mentormarketing_prod"),
        "edit_item" => __("Modifier name", "mentormarketing_prod"),
        "view_item" => __("Voir name", "mentormarketing_prod"),
        "update_item" => __("Mettre à jour le nom de name", "mentormarketing_prod"),
        "add_new_item" => __("Ajouter un nouveau name", "mentormarketing_prod"),
        "new_item_name" => __("Nom du nouveau name", "mentormarketing_prod"),
        "parent_item" => __("Parent dname", "mentormarketing_prod"),
        "parent_item_colon" => __("name parent :", "mentormarketing_prod"),
        "search_items" => __("Recherche de Names", "mentormarketing_prod"),
        "popular_items" => __("Names populaires", "mentormarketing_prod"),
        "separate_items_with_commas" => __("Séparer les Names avec des virgules", "mentormarketing_prod"),
        "add_or_remove_items" => __("Ajouter ou supprimer des Names", "mentormarketing_prod"),
        "choose_from_most_used" => __("Choisir parmi les Names les plus utilisés", "mentormarketing_prod"),
        "not_found" => __("Aucun Names trouvé", "mentormarketing_prod"),
        "no_terms" => __("Aucun Names", "mentormarketing_prod"),
        "items_list_navigation" => __("Navigation de liste de Names", "mentormarketing_prod"),
        "items_list" => __("Liste de Names", "mentormarketing_prod"),
        "back_to_items" => __("Retourner à Names", "mentormarketing_prod"),
        "name_field_description" => __("Le nom tel qu’il apparaîtra sur votre site.", "mentormarketing_prod"),
        "parent_field_description" => __("Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.", "mentormarketing_prod"),
        "slug_field_description" => __("The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.", "mentormarketing_prod"),
        "desc_field_description" => __("The description is not prominent by default; however, some themes may show it.", "mentormarketing_prod"),
    ];


    $args = [
        "label" => __("Names", "mentormarketing_prod"),
        "labels" => $labels,
        "public" => true,
        "publicly_queryable" => true,
        "hierarchical" => false,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => ['slug' => 'names', 'with_front' => true,],
        "show_admin_column" => false,
        "show_in_rest" => true,
        "show_tagcloud" => false,
        "rest_base" => "names",
        "rest_controller_class" => "WP_REST_Terms_Controller",
        "rest_namespace" => "wp/v2",
        "show_in_quick_edit" => false,
        "sort" => false,
        "show_in_graphql" => false,
    ];
    register_taxonomy("names", ["mentormarketing_prod"], $args);
}
add_action('init', 'register_my_taxes');
