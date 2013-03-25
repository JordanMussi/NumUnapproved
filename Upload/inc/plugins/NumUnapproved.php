<?php
/***************************************************************************
 *
 *	Author:	Jordan Mussi
 *	File:	./inc/plugins/NumUnapproved.php
 *  
 *	License:
 *  
 *	This program is free software: you can redistribute it and/or modify it under 
 *	the terms of the GNU General Public License as published by the Free Software 
 *	Foundation, either version 3 of the License, or (at your option) any later 
 *	version.
 *	
 *	This program is distributed in the hope that it will be useful, but WITHOUT ANY 
 *	WARRANTY; without even the implied warranty of MERCHANTABILITY or 
 *	FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License 
 *	for more details.
 *	
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *	
 ***************************************************************************/

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}
$plugins->add_hook("usercp_end", "NumUnapproved_run");

function NumUnapproved_info()
{
	return array(
		"name"			=> "NumUnapproved",
		"description"	=> "Displays the number of unnaproved posts/threads the user has in the UserCP",
		"website"		=> "https://github.com/JordanMussi/NumUnapproved",
		"author"		=> "Jordan Mussi",
		"authorsite"	=> "https://github.com/JordanMussi",
		"guid"          => "2177dda692e8234b9ef5be48109dee6c",
		"version"		=> "1",
		"compatibility" => "16*"
	);
}


function NumUnapproved_activate()
{
	require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("usercp", "#".preg_quote('{$referral_info}')."#i", '<strong>{$lang->NumUnapproved_threads}</strong> {$NumUnapproved[\'threads\']}<br />
<strong>{$lang->NumUnapproved_posts}</strong> {$NumUnapproved[\'posts\']}<br />
{$referral_info}');
}

function NumUnapproved_deactivate()
{
	require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("usercp", "#".preg_quote('
<strong>{$lang->NumUnapproved_threads}</strong> {$NumUnapproved[\'threads\']}<br />
<strong>{$lang->NumUnapproved_posts}</strong> {$NumUnapproved[\'posts\']}<br />')."#i", '');
}

function NumUnapproved_run()
{
	global $db, $mybb, $lang, $NumUnapproved;

	$lang->load('NumUnapproved');
	
	// Generate the unapproved thread count
	$query = $db->simple_select("threads", "*", "visible = '0' AND uid = '{$mybb->user['uid']}'");
	$num_threads = $db->num_rows($query);
	if(!$num_threads)
	{
		$num_threads = 0;
	}
	// Make the numbers look pretty
	$unapproved_threads = my_number_format($num_threads);

	// Generate the unapproved post count
	$query = $db->simple_select("posts", "*", "visible = '0' AND uid = '{$mybb->user['uid']}'");
	$num_posts = $db->num_rows($query);
	if(!$num_posts)
	{
		$num_posts = 0;
	}
	// Make the numbers look pretty
	$unapproved_posts = my_number_format($num_posts);

	$NumUnapproved = array(
	"threads"	=>	$unapproved_threads,
	"posts"	=>	$unapproved_posts
	);
}
?>