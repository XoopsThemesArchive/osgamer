<?php
// $Id: newsfeeder.php
//  ------------------------------------------------------------------------ //
//                HeadLine News XML Feeder for Omnetwork Xoops Theme #7      //
//                    Copyright (c) 2006 Open Mind Network                   //
//                       <http://www.omnetwork.net/>                         //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

// Settings (change values in this block to customize newsfeed
$settings['xoopspath'] = '../../mainfile.php'; // relative path to mainfile
// gathering Xoops setup info
include ($settings['xoopspath']);

$settings['news_number'] = 5; // number of news to rotate
$settings['news_delay'] = 4; // seconds to hold one headline
$settings['news_table'] = XOOPS_DB_PREFIX."_ams_article "; // db table name of news
$settings['news_title_cell'] = "title"; // table cell with title
$settings['news_id_cell'] = "storyid"; // table cell with ID number used in Link
$settings['news_linkbody'] = XOOPS_URL."/modules/AMS/article.php?storyid="; // static part of link
$settings['open_blank'] = 1; // 1 - article in blank windows, 0 in same window

// filtering settings:
$settings['is_topic_filter'] = 1; // 1 - filter SELECT by topic , 0 - no filtering
$settings['topic_to_show'] = 2; // topic id
$settings['topic_cell'] = "topicid"; // name of table cell with topic data
// End of settings

// YOU SHOULDN'T CHANGE ANYTHING BEYOND



// debug
// echo "Xoops host: ".XOOPS_DB_HOST." Xoops database name: ".XOOPS_DB_NAME;

// connecting to database using Xoops settings
$db_veza = mysql_connect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS) or die("Connection to database server failed");
mysql_select_db(XOOPS_DB_NAME, $db_veza) or die("Database selection failed");

// Quering recent news
if ($settings['is_topic_filter']==1) {
$upit = "SELECT * FROM ".$settings['news_table']." WHERE ".$settings['topic_cell']."='".$settings['topic_to_show']."' ORDER BY storyid DESC";
} else {
$upit = "SELECT * FROM ".$settings['news_table']." ORDER BY storyid DESC"; }

$res = mysql_query($upit, $db_veza);

// creating XML feed:
echo '<?xml version="1.0" encoding="utf-8"?>
<xdoc>
<xdata>';

// data from database
for ($a=1; $a<$settings['news_number']+1; $a++) {
$row = mysql_fetch_array($res);
echo '<xnews>
<xtitle>'.$row[$settings['news_title_cell']].'</xtitle>
<xlink>'.$settings['news_linkbody'].$row[$settings['news_id_cell']].'</xlink>
</xnews>';}
echo "</xdata>";

// settings data

echo '<xsettings>
	<xdelay>'.$settings['news_delay'].'</xdelay>
	<xblank>'.$settings['open_blank'].'</xblank>
</xsettings>';

// closing document
echo '</xdoc>';
?>