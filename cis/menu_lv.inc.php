<?php
/* Copyright (C) 2017 fhcomplete.org
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
 *
 * Authors: Andreas Oesterreicher <andreas.oesterreicher@technikum-wien.at>
 */
/**
 * Hinzufuegen von neuen Menuepunkten bei CIS Lehrveranstaltungen
 */
require_once(dirname(__FILE__).'/../config.inc.php');
require_once(dirname(__FILE__).'/../include/moodle_course.class.php');

$showmoodle=false;
$link_target='';
$link_onclick='';
$text='';
$link='';

$stg = new studiengang();
$stg->load($lv->studiengang_kz);
if ($stg->moodle)
	$showmoodle = true;

$moodle = new moodle_course();
if(!$moodle->getAll($lvid, $angezeigtes_stsem))
	echo "ERROR:".$moodle->errormsg;
if (count($moodle->result)>0)
	$showmoodle = true;

if ($angemeldet)
{
	if ($showmoodle)
	{
		$link = APP_ROOT."addons/moodle/cis/moodle_choice.php?lvid=".urlencode($lvid)."&stsem=".urlencode($angezeigtes_stsem);
		if (count($moodle->result) > 0)
		{
			if (!$is_lector)
			{
				$moodle->result=array();
				$moodle->getCourse($lvid, $angezeigtes_stsem, $user);

				if(count($moodle->result)==1)
					$link = ADDON_MOODLE_PATH.'course/view.php?id='.urlencode($moodle->result[0]->mdl_course_id);
				else
					$link = "moodle_choice.php?lvid=".urlencode($lvid)."&stsem=".urlencode($angezeigtes_stsem);
			}
			else
			{
				if (count($moodle->result) == 1)
				{
					$link = ADDON_MOODLE_PATH.'course/view.php?id='.urlencode($moodle->result[0]->mdl_course_id);
				}
				else
				{
					$link = APP_ROOT."addons/moodle/cis/moodle_choice.php";
					$link .= "?lvid=".urlencode($lvid)."&stsem=".urlencode($angezeigtes_stsem);
				}
			}
			$link_target = '_blank';
		}
		else
		{
			$link = '';
		}

		if ($is_lector &&
			(
				!defined('ADDON_MOODLE_LECTOR_CREATE_COURSE')
				|| (defined('ADDON_MOODLE_LECTOR_CREATE_COURSE') && ADDON_MOODLE_LECTOR_CREATE_COURSE)
			))
		{
			$wartungPath = APP_ROOT.'addons/moodle/cis/moodle_wartung.php';
			$wartungPath .= '?lvid='.urlencode($lvid).'&stsem='.urlencode($angezeigtes_stsem);

			$handbuchPath = APP_ROOT.'cms/dms.php?id='.$p->t('dms_link/moodleHandbuch24');
			$text.= '<a href="'.$wartungPath.'" class="Item">'.$p->t('moodle/wartung').'</a>
				<br /><a href="'.$handbuchPath.'" class="Item" target="_blank">'.$p->t('moodle/handbuch').'</a>';
		}
	}
}

if ($showmoodle)
{
	$menu[] = array
	(
		'id' => 'addon_moodle_menu_moodle',
		'position' => '70',
		'name' => $p->t('moodle/moodle'),
		'icon' => '../../../addons/moodle/skin/images/button_moodle.png',
		'link' => $link,
		'link_target' => $link_target,
		'link_onclick' => $link_onclick,
		'text' => $text
	);
}
?>