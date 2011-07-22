<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2010-2011
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @author     Leo Unglaub <leo.unglaub@iserv.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id$
 */


/**
 * List
 */
$GLOBALS['TL_DCA']['tl_style']['list']['sorting']['child_record_callback'] = array('Cufon', 'listStyles');


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_style']['palettes']['__selector__'][]	= 'cufon';
$GLOBALS['TL_DCA']['tl_style']['palettes']['default']			= str_replace(';{list_legend}', ',cufon;{list_legend}', $GLOBALS['TL_DCA']['tl_style']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_style']['subpalettes']['cufon']			= 'cufon_font,cufon_fontFamily,cufon_hover,cufon_options';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_style']['fields']['cufon'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_style']['cufon'],
	'inputType'			=> 'checkbox',
	'eval'				=> array('submitOnChange'=>true, 'tl_class'=>'clr'),
);

$GLOBALS['TL_DCA']['tl_style']['fields']['cufon_font'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_style']['cufon_font'],
	'inputType'			=> 'fileTree',
	'eval'				=> array('mandatory'=>true, 'fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'extensions'=>'js,woff,ttf,otf,svg,eot', 'tl_class'=>'clr'),
);

$GLOBALS['TL_DCA']['tl_style']['fields']['cufon_fontFamily'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_style']['cufon_fontFamily'],
	'inputType'			=> 'text',
	'eval'				=> array('maxlength'=>255, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_style']['fields']['cufon_hover'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_style']['cufon_hover'],
	'inputType'			=> 'text',
	'eval'				=> array('maxlength'=>255, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_style']['fields']['cufon_options'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_style']['cufon_options'],
	'inputType'			=> 'multitextWizard',
	'eval'				=> array('tl_class'=>'clr', 'style'=>'width:100%', 'columns'=>array(array('name'=>'o', 'label'=>&$GLOBALS['TL_LANG']['tl_style']['cufon_o'], 'mandatory'=>true), array('name'=>'v', 'label'=>&$GLOBALS['TL_LANG']['tl_style']['cufon_v'], 'mandatory'=>true)))
);

