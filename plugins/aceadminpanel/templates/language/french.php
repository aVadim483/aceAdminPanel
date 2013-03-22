<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 2.0.382
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 1.0.1
 * @File Name: %%filename%%
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

/**
 * Fichier de langue pour le module (langue française)
 */
return array(
    'adm_title' => 'Adminpanel',
    'adm_need_upgrade' => '<b>MISE EN GARDE</b> Vous devez mettre à jour le module en cliquant sur le lien ci-dessous',
    'adm_denied_title' => 'Erreur d\'accès',
    'adm_denied_text' => 'Vous n\'avez pas accès à ce régime',
    'adm_banned1_text' => 'Vous êtes vu refuser l\'accès à ce site pour %%date%%',
    'adm_banned2_text' => 'Vous êtes vu refuser l\'accès à ce site',
    'adm_banned3_text' => 'Refuser l\'accès à ce site à partir de votre adresse IP',
    'adm_user_login' => 'Connexion utilisateur',
    'adm_user_not_found' => 'Utilisateur %%user%% Introuvable',
    'adm_user_ip' => 'IP de l\'utilisateur',
    'adm_user_ip_seek_notice'=> 'Par exemple, 83.167.100.46 ou 83.167 .*.*',
    'adm_user_regdate_seek_notice' => 'Par exemple, 2009-2 ou 31.03.2009',

    'adm_menu_about' => 'Module',
    'adm_menu_params' => 'Options',

    'adm_menu_site' => 'Site',
    'adm_menu_statistics' => 'Statistiques',
    'adm_menu_plugins' => 'Plugins',
    'adm_menu_delegates' => 'Délégation',
    'adm_menu_settings' => 'Paramètres',
    'adm_menu_config' => 'Configuration',
    'adm_menu_logs' => 'Revues',
    'adm_menu_close_site' => 'Fermer Site',
    'adm_menu_cache' => 'La mise en cache',
    'adm_menu_reset' => 'Remise',
    'adm_menu_reset_cache' => 'Vider le cache',
    'adm_menu_reset_config' => 'Réinitialiser la configuration',

    'adm_menu_themes' => 'Sujets',
    'adm_menu_installed' => 'Installé',
    'adm_theme_activate' => 'Activer',

    'adm_menu_languages' => 'Langues',
    'adm_menu_installed' => 'Installé',

    'adm_menu_pages' => 'Pages',
    'adm_menu_pages_list' => 'Liste',
    'adm_menu_pages_new' => 'Nouveau',
    'adm_menu_pages_options' => 'Options',

    'adm_menu_blogs' => 'Blogs',
    'adm_menu_blogs_list' => 'Liste',

    'adm_menu_users' => 'Communauté',
    'adm_menu_users_profile' => 'Profil',
    'adm_menu_users_list' => 'Liste',
    'adm_menu_users_banlist' => 'Liste Ban',
    'adm_banlist_ids' => 'Communauté',
    'adm_banlist_ips' => 'Adresse IP',

    'adm_site_statistics' => 'Statistiques du site',
    'adm_site_stat_users' => 'Membres:',
    'adm_site_stat_blogs' => 'Blogs:',
    'adm_site_stat_topics' => 'Topique:',
    'adm_site_stat_comments' => 'Commentaires:',

    'adm_active_plugins' => 'Plugins actifs',

    'adm_params_title' => 'Options adminpanel',
    'adm_plugins_title' => 'Activation / désactivation des plugins',

    'adm_users_list' => 'Communauté',
    'adm_admins_list' => 'Administrateurs',
    'adm_users_date_reg' => 'Inscrit',
    'adm_users_ip_reg' => 'D\'enregistrement de propriété intellectuelle',
    'adm_users_activated' => 'Activé',
    'adm_users_last_activity' => 'Dernière activité',
    'adm_users_banned' => 'Banned',
    'adm_users_activate' => 'Activer',
    'adm_users_action' => 'Action',
    'adm_users_ban' => 'Banned',
    'adm_users_unban' => 'Unban',
    'adm_cannot_ban_self' => 'Vous ne pouvez pas eux-mêmes interdiction',
    'adm_cannot_be_banned' => 'Vous ne pouvez pas admin utilisateurs bannis',
    'adm_already_added' => 'Cet utilisateur est déjà un administrateur',
    'adm_cannot_ban_admin' => 'Vous ne pouvez pas interdire à un administrateur',
    'adm_cannot_with_admin' => 'Impossible de faire fonctionner avec l\'admin utilisateur',
    'adm_users_del' => 'Effacer',
    'adm_users_del_warning' => 'MISE EN GARDE Supprimer un utilisateur, il va supprimer ses blogs, des sujets, des commentaires, de voter. Peut être supprimé que les commentaires des autres utilisateurs, écrite en réponse aux commentaires supprimés',
    'adm_users_del_confirm' => 'Confirmer supprimer des utilisateurs',
    'adm_cannot_del_self' => 'Vous ne pouvez pas se retirer',
    'adm_cannot_del_admin' => 'Vous ne pouvez pas supprimer un administrateur',
    'adm_cannot_del_confirm' => 'Vous n\'avez pas confirmé le retrait de l\'utilisateur',
    'adm_user_deleted' => 'Utilisateur %%user%% supprimé',
    'adm_msg_sent_ok' => 'Le message a été envoyé',

    'adm_user_voted' => 'Vote',
    'adm_user_voted_users' => 'Pour les utilisateurs',
    'adm_user_voted_topics' => 'Pour les sujets',
    'adm_user_voted_blogs' => 'Dernière blogs',
    'adm_user_voted_comments' => 'Les derniers commentaires',
    'adm_user_wrote_topics' => 'Posté le sujet ou',
    'adm_user_wrote_comments' => 'Les observations écrites',
    'adm_comment_edit' => 'Modification de commentaire',

    'adm_param_items_per_page' => 'Le nombre de lignes par page',
    'adm_param_items_per_page_notice' => 'Le nombre de lignes affichées sur une page dans les listes de table (les utilisateurs, les listes d\'interdiction, etc)',
    'adm_param_votes_per_page' => 'Le nombre de sondages récents dans le profil',
    'adm_param_votes_per_page_notice' => 'Le nombre de sondages récents qui sont indiqués dans le profil de l\'utilisateur dans chaque tableau - un vote pour les autres utilisateurs, de leurs sujets, les commentaires',
    'adm_param_edit_footer' => 'Signature commentaires édités ou sujet',
    'adm_param_edit_footer_notice' => 'Signature, qui sera automatiquement ajoutée lors de l\'édition ou le sujet avec les commentaires de adminpanel',
    'adm_param_vote_value' => 'Pouvoir de l\'administrateur de vote',
    'adm_param_vote_value_notice' => 'Le vote "amélioré, disponible dans le profil de l\'utilisateur dans adminpaneli',

    'adm_ban_upto' => 'Ban ou',
    'adm_ban_unlim' => 'Ban indéterminée',
    'adm_ban_for' => 'Interdiction de',
    'adm_ban_days' => 'journées',
    'adm_ban_comment' => 'Commentaire',

    'adm_pages' => 'Pages statiques',
    'adm_pages_new' => 'Nouvelle page',
    'adm_pages_options' => 'Options',
    'adm_page_options_urls' => 'URL réservés',
    'adm_page_options_urls_notice' => 'Réservés URL (séparés par des virgules) qui ne peuvent pas être utilisés lors de la création de nouvelles pages',

    'adm_themes' => 'Sujets',
    'adm_close_open_site' => 'Fermer / Ouvrir le',
    'adm_site_closed' => 'Site fermé',
    'adm_site_openned' => 'Le site est ouvert',
    'adm_close_site_notice' => 'Vous pouvez fermer le site par les visiteurs. Dans ce mode, seuls les administrateurs ont accès au site.',
    'adm_close_site_text_notice' => 'Entrez le texte qui permettra aux visiteurs de site web privé',
    'adm_close_site_file_notice' => 'Ou entrez le nom du fichier HTML qui redirige les visiteurs (doit être dans le dossier racine)',
    'adm_close_site_text_empty' => 'Messages texte ne peut pas être vide',
    'adm_close_site_file_empty' => 'Le nom du fichier ne peut pas être vide',

    'adm_yes' => 'Qui',
    'adm_no' => 'Non',
    'adm_include' => 'Ajouter',
    'adm_exclude' => 'Effacer',
    'adm_save' => 'Sauver',
    'adm_reset' => 'Remise',
    'adm_continue' => 'Procéder',
    'adm_saved_ok' => 'Les données stockées',
    'adm_saved_err' => 'Erreur sauvegarde des données',
    'adm_file_not_found' => 'File Not Found',
    'adm_err_read' => 'Erreur de lecture',
    'adm_err_read_dir' => 'Dossier Erreur de lecture',
    'adm_err_read_file' => 'Erreur de lecture de fichier',
    'adm_err_copy_file' => 'La copie de fichiers d\'erreur%%file%%',
    'adm_err_wrong_ip' => 'Blancs adresse IP',
    'adm_config_err_read' => 'Erreur de lecture du fichier de configuration',
    'adm_config_err_backup' => 'Erreur de création du fichier de sauvegarde',
    'adm_config_err_save' => 'Erreur de sauvegarde du fichier de configuration',
    'adm_config_save_ok' => 'Mise à jour du fichier de configuration est enregistré',
    'adm_themes_err_read' => 'Ordre de lecture d\'erreur',
    'adm_themes_select_skin' => 'Choisir un thème',
    'adm_themes_activate_skin' => 'Activer thème',
    'adm_themes_activate_label' => 'Sélectionnez une rubrique pour activer',
    'adm_themes_activate_notice' => 'Sélectionnez une rubrique pour activer la liste des installés de façon',
    'adm_themes_need_files' => 'Pour fonctionner correctement, le thème, les fichiers suivants:',
    'adm_themes_need_files_copy' => 'Les copier à partir du thème actuel?',
    'adm_themes_changed' => 'Sous réserve de modifications. Nécessité d\'actualiser la page',
    'adm_activate_language' => 'Activer le language',
    'adm_compare_language' => 'Comparer les fichiers de langue',
    'adm_languages_select' => 'Sélectionnez la langue',
    'adm_languages_activate' => 'Activer',
    'adm_languages_compare' => 'Comparer',
    'adm_languages_default' => 'Par défaut',
    'adm_languages_activate_label' => 'Sélectionnez une langue pour l\'activation',
    'adm_languages_activate_notice' => 'Sélectionnez la langue pour activer la liste des langues installées',
    'adm_language_not_found' => 'La langue n\'est pas définie',
    'adm_current_language' => 'La langue courante',
    'adm_selected_language' => 'Langue choisie',

    'adm_send_copy_self' => 'Envoyer une copie à vous-même',
    'adm_send_err_to_user' => 'Erreur en envoyant le message à l\'utilisateur %%user%%',
    'adm_send_common_message' => 'Envoyer un message général',
    'adm_send_separate_messages' => 'Envoyer des messages individuels',
    'adm_send_common_notice' => 'Les clients recevront un message général et de toute réponse elle verra tous les autres destinataires',
    'adm_send_separate_notice' => 'Chaque utilisateur reçoit un message distinct personnels',

    'adm_logs_title' => 'Paramètres du journal',
    'adm_logs_users_enable_title' => 'Un journal',
    'adm_logs_users_enable_notice' => 'Vous pouvez activer / désactiver la journalisation des actions de l\'utilisateur',
    'adm_logs_turned_on' => 'Activé',
    'adm_logs_turned_off' => 'De',
    'adm_logs_users_file' => 'Le nom du fichier log de l\'utilisateur actions',
    'adm_logs_users_file_notice' => 'Entrez le nom du fichier de log des actions de l\'utilisateur, se trouve dans les journaux',
    'adm_logs_users_debug' => 'Inclure des informations de débogage',
    'adm_logs_users_debug_notice' => 'Le journal comprendra des informations de débogage (pile d\'appel)',
    'adm_logs_users_logins' => 'Inclure les journaux que pour les utilisateurs',
    'adm_logs_users_logins_notice' => 'Listes de l\'utilisateur connexions séparées par des virgules sans espaces. S\'il est spécifié, les journaux ne sont maintenues que pour ces utilisateurs, ou d\'être un journal unique pour tous',

    'adm_cache_title' => 'Paramètres du cache',
    'adm_cache_not_used' => 'Unassigned',
    'adm_cache_file' => 'Cache de fichiers',
    'adm_cache_memory' => 'Utilisez Memcached',
    'adm_cache_type' => 'Type Cache',
    'adm_cache_prefix' => 'Préfixe mise en cache',
    'adm_cache_prefix_notice' => 'Requis si plusieurs sites utilisent un magasin cache commune',
    'adm_cache_clean' => 'Vider le cache',
    'adm_cache_clean_notice' => 'Définir si vous souhaitez réinitialiser le cache',

    'adm_logs_admin_enable_title' => 'Action administrateurs Connectez-vous',
    'adm_logs_admin_enable_notice' => 'Vous pouvez activer / désactiver la journalisation des actions d\'administrateur',
    'adm_logs_admin_file' => 'Le nom du fichier journal d\'action des administrateurs',
    'adm_logs_admin_file_notice' => 'Spécifiez le nom du fichier journal administrateurs d\'action, est situé dans le logs',

    'adm_logs_max_size' => 'La taille maximale du fichier journal',
    'adm_logs_max_size_notice' => 'Lorsque cette taille est atteinte, une copie et crée un nouveau fichier. Si zéro, alors le nouveau fichier est créé chaque jour',
    'adm_logs_max_files' => 'Nombre de copies de fichiers de log',
    'adm_logs_max_files_notice' => 'Le nombre de copies de sauvegarde de fichiers journaux qui sont stockés sur le site',

    'adm_blog_edit' => 'Modifier blog',
    'adm_blog_delete' => 'Supprimer blog',
    'adm_blog_del_confirm' => 'Blog &quot;%%blog%%&quot; sera supprimé définitivement de tous ses contenus. \nContinuer?',

    'adm_topic_edit' => 'Modifier le sujet',
    'adm_topic_delete' => 'Supprimer le sujet',
    'adm_topic_del_confirm' => 'Sujet &quot;%%topic%%&quot; sera supprimé définitivement de tous ses contenus. \nContinuer?',

    'adm_invite_code' => 'Invitation Code',
    'adm_invite_user_from' => 'Expéditeur',
    'adm_invite_user_to' => 'Bénéficiaire',
    'adm_invite_date_add' => 'Créé',
    'adm_invite_date_used' => 'Date de',
    'adm_send_invite_mail' => 'Envoyer les invitations par e-mail',
    'adm_invite_mode_mail' => 'Envoyer les invitations par e-mail',
    'adm_invite_mode_text' => 'Générer des invitations et de montrer',
    'adm_invite_submit' => 'Générer des invitations',
    'adm_invaite_mail_empty' => 'Vous devez spécifier au moins un e-mail',
    'adm_invaite_text_empty' => 'Nombre d\'invitations devait être supérieur à zéro',
    'adm_invaite_mail_done' => 'Nouvelles invitations envoyées: %%num%%',
    'adm_invaite_text_done' => 'Créé nouvelles invitations: %%num%%',

    'adm_param_check_password' => 'Vérifiez le mot de passe administrateur',
    'adm_param_check_password_notice' => 'S\'il est défini, il vérifie la qualité du mot de passe administrateur sur la fiabilité',

    'adm_password_quality' => 'Avez-vous un mot de passe très faible! Il est fortement recommandé de changer le mot de passe en cours!',

    'adm_act_activate' => 'Activer',
    'adm_act_deactivate' => 'Désactiver',

    'adm_action_ok' => 'Commande complète',
    'adm_action_err' => 'Erreur de commande',

    'adm_cache_title' => 'Paramètres du cache',
    'adm_cache_type' => 'Type Cache',
    'adm_cache_type_notice' => 'Type <b>memory</b> utilise memcached',
    'adm_cache_prefix' => 'Préfixe mise en cache',
    'adm_cache_prefix_notice' => 'Doit être unique pour chaque site afin que vous puissiez plusieurs sites avec un total de stockage du cache',
    'adm_cache_clear_data' => 'Nettoyage du cache de données',
    'adm_cache_clear_data_notice' => 'Réinitialiser le cache de données de stockage',
    'adm_cache_clear_headfiles' => 'Nettoyage de la JS-cache et les fichiers CSS',
    'adm_cache_clear_headfiles_notice' => 'Réinitialiser le cache js de stockage et des fichiers CSS',
    'adm_cache_clear_smarty' => 'Vider le cache Smarty',
    'adm_cache_clear_smarty_notice' => 'Réinitialisation de la banque cache des fichiers compilés Smarty',

    'adm_reset_config_data' => 'les changements de configuration Reset',
    'adm_reset_config_data_notice' => 'Tous les paramètres que vous avez changé par adminpanel, sera remis à sa valeur initiale, à savoir ces tâches dans les fichiers de configuration',

    'adm_plugin_file_not_found' => 'Plugin Fichiers <b>%%file%%</b> Introuvable',
    'adm_plugin_havenot_getversion_method' => 'plug requis <b>%%plugin%%</b> ne retourne pas un numéro de version (il n\'existe aucune méthode <b>GetVersion()</b>)',
    'adm_plugin_activation_reqversion_error_eq' => 'Pour utiliser le plugin nécessaire d\'activer le plugin <b>%%plugin%%</b> Version <b>%%version%%</b>',
    'adm_plugin_activation_reqversion_error_ge' => 'Pour utiliser le plugin nécessaire d\'activer le plugin <b>%%plugin%%</b> Une version plus récente <b>%%version%%</b>',
    'adm_plugin_activation_reqversion_error_gt' => 'Pour utiliser le plugin nécessaire d\'activer le plugin <b>%%plugin%%</b> La version ci-dessus <b>%%version%%</b>',
    'adm_plugin_activation_reqversion_error_le' => 'Pour utiliser le plugin nécessaire d\'activer le plugin <b>%%plugin%%</b> Pas de version supérieur <b>%%version%%</b>',
    'adm_plugin_activation_reqversion_error_lt' => 'Pour utiliser le plugin nécessaire d\'activer le plugin <b>%%plugin%%</b> version ci-dessous <b>%%version%%</b>',

    'adm_action_for_admin_only' => 'Cette action est uniquement disponible pour les administrateurs',

    'adm_cannot_clear_dir' => 'Impossible de nettoyer votre %%dir%%. Il est recommandé de le faire manuellement',

    'adm_plugin_priority_notice' => 'Plugins seront chargés dans l\'ordre dans lequel ils apparaissent dans le tableau. Vous pouvez modifier l\'ordre de démarrage des plug-ins en utilisant les flèches à la colonne de droite',
    'adm_plugin_priority_up' => 'Augmenter la priorité de',
    'adm_plugin_priority_down' => 'Réduire la priorité',

    'adm_execute' => 'Courir',

    'adm_text_about' =>
        'Author: aVadim<br/>
        E-mail: vadim483@gmail.com<br/>
        ',
    'adm_text_donate' =>
        'Pour ceux qui veulent s\'intégrer dans un matériau noble et d\'encourager le plugin auteur,
        annoncera les détails pour les dons: Porte-monnaie
        WebMoney <b>Z178319650868</b> или <b>R312496642374</b>, счет Yandex.Money <b>41001176375531</b>.',
);

// EOF