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
	('mailer_authentication', '','text',1, 'mailer')
;