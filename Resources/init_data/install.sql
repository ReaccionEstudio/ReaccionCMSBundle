# Config
INSERT INTO config (name,value,type,required) VALUES 
	('small.image.size','a:2:{s:6:"height";i:150;s:5:"width";i:150;}','serialized',1),
	('medium.image.size','a:2:{s:6:"height";i:800;s:5:"width";i:600;}','serialized',1),
	('large.image.size','a:2:{s:6:"height";i:1600;s:5:"width";i:1200;}','serialized',1),
	('language','en',null,1),
	('themes_path','templates/ReaccionCMSBundle/themes/','text',1),
	('current_theme','rocket_theme','text',1),
	('site_name','ReaccionCMS','text',1),
	('admin_logo','images/reaccion_cms_logo.jpg',null,1),
	('entries_list_pagination_limit',10,'number',1),
	('entries_resume_characters_length', 350,'number',1),
	('entries_comments_pagination_limit', 10,'number',1)
;