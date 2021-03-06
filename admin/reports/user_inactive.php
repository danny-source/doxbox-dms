<?php
/**
 * user_inactive.php
 *
 * Author: Steve Bourgeois <owl@bozzit.com>
 *
 * Copyright (c) 2006-2009 Bozz IT Consulting Inc
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * $Id: user_inactive.php,v 1.1.1.1 2005/11/06 15:40:59 b0zz Exp $
 */


$CountLines = 0;
$sql = new Owl_DB;
$since = $_POST['since'];

if (empty($since))
{
   $since = $sql->now();
}
else
{
  $since = date("Y-m-d H:i:s", strtotime($since));
  $since = "'" . $since . "'";
}

$sql->query("SELECT * from $default->owl_users_table WHERE lastlogin < $since ORDER BY name");

if (empty($export))
{
   $xtpl->assign('REPORT_FILTER_LABEL', $owl_lang->report_filter_since);
   $xtpl->assign('REPORT_FILTER_VALUE', ereg_replace("'", "",$since));

   $xtpl->assign('REPORT_BTN_SUBMIT_LABEL', $owl_lang->btn_submit);
   $xtpl->assign('REPORT_BTN_SUBMIT_ALT', $owl_lang->btn_submit_alt);

   $xtpl->assign('REPORT_BTN_EXPORT_LABEL', $owl_lang->btn_export);
   $xtpl->assign('REPORT_BTN_EXPORT_ALT', $owl_lang->btn_export_alt);
   //print("<tr>\n");
   //print("<td class=\"form1\">$owl_lang->report_filter_since</td>\n");
   //print("<td colspan=\"3\" class=\"form1\" width=\"100%\">");
   //print("<input class=\"finput1\" type=\"text\" name=\"since\" value=\"" . ereg_replace("'", "",$since) ."\"></input>");
   //fPrintSubmitButton($owl_lang->btn_submit, "Submit");
   //fPrintSubmitButton($owl_lang->btn_export , "Export", "submit", "export");
   //print("</td>");
   //print("</tr>\n");
   
   //print("<tr>\n");
   //print("<td align=\"left\" colspan=\"3\">&nbsp;</td>\n");
   //print("<td align=\"left\">&nbsp;</td>\n");
   //print("</tr>\n");
   //print("<tr>\n");
   
   // 
   // User File Stats BEGIN
   // 
   
   $xtpl->assign('REPORT_TITLE', $owl_lang->report_users_inactive_title);
   //print("<td class=\"admin2\" align=\"left\" colspan=\"4\">$owl_lang->report_users_inactive_title</td>\n");
   //print("<td align=\"left\">&nbsp;</td>\n");
   //print("</tr>\n");
   //print("<tr>\n");
   //print("<td align=\"left\" colspan=\"3\">&nbsp;</td>\n");
   //print("<td align=\"left\">&nbsp;</td>\n");
   //print("</tr>\n");
   //print("<tr>\n");

   $xtpl->assign('REPORT_NAME_TITLE', $owl_lang->name);
   $xtpl->assign('REPORT_USERNAME_TITLE', $owl_lang->username);
   $xtpl->assign('REPORT_LASTLOG_TITLE', $owl_lang->last_logged);
   //print("<td align=\"left\" class=\"title1\">$owl_lang->name</td>\n");
   //print("<td align=\"left\" class=\"title1\">$owl_lang->username</td>\n");
   //print("<td align=\"left\" colspan=\"2\" width=\"100%\" class=\"title1\">$owl_lang->last_logged</td>\n");
   //print("</tr>\n");
}
else
{
   header( 'Pragma: ' );
   header( 'Cache-Control: ' );
   header( 'Content-Type: application/vnd-ms.excel' );
   $aDate = getdate();
   $sExportFilename = 'User_Inactive_' . $aDate[ 'month' ] . '_' . $aDate[ 'mday' ] . '_' . $aDate[ 'year' ] . '.xls';
   header( 'Content-Disposition: attachment; filename="' . $sExportFilename . '"' );
   print($owl_lang->name . "\t");
   print($owl_lang->username . "\t");
   print($owl_lang->last_logged . "\t\n");
}
   
   while ($sql->next_record())
   {
      $CountLines++;
      $PrintLines = $CountLines % 2;
      if ($PrintLines == 0)
      {
         $sTrClass = "file1";
      }
      else
      {  
         $sTrClass = "file2";
      }
      $xtpl->assign('REPORT_TD_STYLE', $sTrClass);
   
   if (empty($export))
   {
      $xtpl->assign('REPORT_NAME_VALUE', $sql->f("name"));
      $xtpl->assign('REPORT_USERNAME_VALUE', $sql->f("username"));
      $xtpl->assign('REPORT_LASTLOG_VALUE', date($owl_lang->localized_date_format, strtotime($sql->f("lastlogin"))));
      //print("\t\t\t\t<tr>\n");
      //print("<td class=\"$sTrClass\">" . $sql->f("name") . "</td>\n");
      //print("<td class=\"$sTrClass\">" . $sql->f("username") . "</td>\n");
      //print("<td class=\"$sTrClass\" colspan=\"2\">" .  date($owl_lang->localized_date_format, strtotime($sql->f("lastlogin"))) . "</td>\n");
      //print("</tr>\n");
      $xtpl->parse('main.Stats.Report'.$execreport.'.Users');

   }
   else
   {
      print($sql->f("name") . "\t");
      print($sql->f("username") . "\t");
      print(date($owl_lang->localized_date_format, strtotime($sql->f("lastlogin"))) . "\t\n");
   }

} 

// 
// User File Stats END
?>
