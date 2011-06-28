-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_style`
-- 

CREATE TABLE `tl_style` (
  `cufon` char(1) NOT NULL default '',
  `cufon_font` blob NULL,
  `cufon_fontFamily` varchar(255) NOT NULL default '',
  `cufon_hover` varchar(255) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

