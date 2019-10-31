require("../images/reaccion_estudio.png");
require("../images/user_nav_icon.png");
require("./bootstrap-4-navbar.js");

import Menu from './Menu.js';
import FlashMessages from './FlashMessages.js';
import Comment from './Comment.js';
import CommonEvents from './CommonEvents.js';

let menu = new Menu();
	menu.events();

let flashMessages = new FlashMessages();
	flashMessages.events();

let comment = new Comment();
	comment.events();

let commonEvents = new CommonEvents();
	commonEvents.languageSwitcher();
