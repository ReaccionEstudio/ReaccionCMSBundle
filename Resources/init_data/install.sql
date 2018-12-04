# Config
INSERT INTO config (name,value,type,required) VALUES 
	('small.image.size','a:2:{s:6:"height";i:150;s:5:"width";i:150;}','serialized',1),
	('medium.image.size','a:2:{s:6:"height";i:800;s:5:"width";i:600;}','serialized',1),
	('large.image.size','a:2:{s:6:"height";i:1600;s:5:"width";i:1200;}','serialized',1),
	('language','en',null,1),
	('themes_path','templates/ReaccionCMSBundle/themes/',null,1),
	('current_theme','rocket_theme',null,1),
	('site_name','ReaccionCMS',null,1),
	('admin_logo','images/reaccion_cms_logo.jpg',null,1)
;