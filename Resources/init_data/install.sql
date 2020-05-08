# Config
INSERT INTO config (name,value,type,required, groups) VALUES 
	('small.image.size','a:2:{s:6:"height";i:150;s:5:"width";i:150;}','serialized',1, null),
	('medium.image.size','a:2:{s:6:"height";i:800;s:5:"width";i:600;}','serialized',1, null),
	('large.image.size','a:2:{s:6:"height";i:1600;s:5:"width";i:1200;}','serialized',1, null),
	('language','en', 'text',1, null),
	('themes_path','templates/ReaccionCMSBundle/themes/','text',1, null),
	('current_theme','rocket_theme','text',1, null),
	('site_name','ReaccionCMS','text',1, null),
	('admin_logo','images/reaccion_cms_logo.jpg',null,1, null),
	('entries_list_pagination_limit',10,'number',1, null),
	('entries_resume_characters_length', 350,'number',1, null),
	('entries_comments_pagination_limit', 10,'number',1, null),
	('mailer_host', 'localhost','text',1, 'mailer'),
	('mailer_port', 25,'number',1, 'mailer'),
	('mailer_username', '','text',1, 'mailer'),
	('mailer_password', '','password',1, 'mailer'),
	('mailer_authentication', '','text',1, 'mailer'),
	('email_templates_path', 'templates/ReaccionCMSBundle/emailTemplates/','text',1, null),
	('show_languages_switcher', 1,'boolean',1, 'frontend'),
	('user_registration', 1,'boolean',1, 'frontend')
;

# Email templates
INSERT INTO email_templates (name,`type`,slug,message,subject,fromname,fromemail,`language`,template_file,plain_text,enabled,created_at,updated_at,message_params) VALUES 
('Test email','reaccion-cms','test-email','<p>Hi there,</p><p>Your email settings are valid and ReaccionCMS has been connected successfully with the SMTP server.</p><p>King regards!</p>','ReaccionCMS test email','Reacción CMS','alberto@albertolabs.com','en','default.html.twig',0,1,'2019-01-08 01:11:26.000','2019-01-11 19:18:28.000',NULL);

# Home page
INSERT INTO `pages` (`id`,`translation_group_id`,`name`,`slug`,`enabled`,`type`,`main_page`,`language`,`template_view`,`options`,`seo_title`,`seo_description`,`seo_keywords`,`created_at`,`updated_at`)
VALUES (1,NULL,'Home','home',1,NULL,1,'en','page.html.twig',NULL,NULL,NULL,NULL,now(),now());
