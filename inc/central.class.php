<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2017 Teclib' and contributors.
 *
 * http://glpi-project.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

/** @file
* @brief
*/

use Glpi\Event;

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * Central class
**/
class Central extends CommonGLPI {


   static function getTypeName($nb = 0) {

      // No plural
      return __('Standard interface');
   }


   function defineTabs($options = []) {

      $ong = [];
      $this->addStandardTab(__CLASS__, $ong, $options);

      return $ong;
   }


   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      if ($item->getType() == __CLASS__) {
         $tabs[1] = __('Recordatorios');/*__('Personal View');
         $tabs[2] = __('Group View');
         $tabs[3] = __('Global View');
         $tabs[4] = _n('RSS feed', 'RSS feeds', Session::getPluralNumber());*/

         return $tabs;
      }
      return '';
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {

      if ($item->getType() == __CLASS__) {
         switch ($tabnum) {
            case 1 : // all
               $item->showMyView();
               break;

            /*case 2 :
               $item->showGroupView();
               break;

            case 3 :
               $item->showGlobalView();
               break;

            case 4 :
               $item->showRSSView();
               break;*/
         }
      }
      return true;
   }


   /**
    * Show the central global view
   **/
   /*static function showGlobalView() {

      $showticket  = Session::haveRight("ticket", Ticket::READALL);
      $showproblem = Session::haveRight("problem", Problem::READALL);

      echo "<table class='tab_cadre_central'><tr class='noHover'>";
      echo "<td class='top' width='50%'>";
      echo "<table class='central'>";
      echo "<tr class='noHover'><td>";
      if ($showticket) {
         Ticket::showCentralCount();
      } else {
         Ticket::showCentralCount(true);
      }
      if ($showproblem) {
         Problem::showCentralCount();
      }
      if (Contract::canView()) {
         Contract::showCentral();
      }
      echo "</td></tr>";
      echo "</table></td>";

      if (Session::haveRight("logs", READ)) {
         echo "<td class='top'  width='50%'>";

         //Show last add events
         Event::showForUser($_SESSION["glpiname"]);
         echo "</td>";
      }
      echo "</tr></table>";

      if ($_SESSION["glpishow_jobs_at_login"] && $showticket) {
         echo "<br>";
         Ticket::showCentralNewList();
      }
   }*/


   /**
    * Show the central personal view
   **/
   static function showMyView() {
      global $DB, $CFG_GLPI;

      $showticket  = Session::haveRightsOr("ticket",
                                           [Ticket::READMY, Ticket::READALL, Ticket::READASSIGN]);

      $showproblem = Session::haveRightsOr('problem', [Problem::READALL, Problem::READMY]);

      echo "<table class='tab_cadre_central'>";

      Plugin::doHook('display_central');

      $warnings = [];
      if (Session::haveRight("config", UPDATE)) {
         $logins = User::checkDefaultPasswords();
         $user   = new User();
         if (!empty($logins)) {
            $accounts = [];
            foreach ($logins as $login) {
               $user->getFromDBbyName($login);
               $accounts[] = $user->getLink();
            }
            $warnings[] = sprintf(__('For security reasons, please change the password for the default users: %s'),
                               implode(" ", $accounts));
         }
         if (file_exists(GLPI_ROOT . "/install/install.php")) {
            $warnings[] = sprintf(__('For security reasons, please remove file: %s'),
                               "install/install.php");
         }
      }
      
      //ADVERTENCIA PARA DESARROLLADORES(USAR DEBUGGER)
        
      if ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE) {
         if (!DBMysql::isMySQLStrictMode($comment)) {
            $warnings[] = sprintf(__('SQL strict mode is not fully enabled, recommended for development: %s'), $comment);
         }
      }

      if ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE) {
         $crashedtables = DBMysql::checkForCrashedTables();
         if (!empty($crashedtables)) {
            $tables = [];
            foreach ($crashedtables as $crashedtable) {
               $tables[] = $crashedtable['table'];
            }
            $message = __('The following SQL tables are marked as crashed:');
            $message.= implode(',', $tables);
            $warnings[] = $message;
         }
      }

      if ($DB->isSlave()
          && !$DB->first_connection) {
         $warnings[] = __('SQL replica: read only');
      }

      if (count($warnings)) {
         echo "<tr><th colspan='2'>";
         echo "<div class='warning'>";
         echo "<i class='fa fa-exclamation-triangle fa-5x'></i>";
         echo "<ul><li>" . implode('</li><li>', $warnings) . "</li></ul>";
         echo "<div class='sep'></div>";
         echo "</div>";
         echo "</th></tr>";
      }

      echo "<tr class='noHover'><td class='top' width='50%'><table class='central'>";
      echo "<tr class='noHover'><td>";
      if (Session::haveRightsOr('ticketvalidation', TicketValidation::getValidateRights())) {
         Ticket::showCentralList(0, "tovalidate", false);
      }
      if ($showticket) {

         if (Ticket::isAllowedStatus(Ticket::SOLVED, Ticket::CLOSED)) {
            Ticket::showCentralList(0, "toapprove", false);
         }

         Ticket::showCentralList(0, "survey", false);

         Ticket::showCentralList(0, "rejected", false);
         Ticket::showCentralList(0, "requestbyself", false);
         Ticket::showCentralList(0, "observed", false);

         Ticket::showCentralList(0, "process", false);
         Ticket::showCentralList(0, "waiting", false);

         TicketTask::showCentralList(0, "todo", false);

      }
      if ($showproblem) {
         Problem::showCentralList(0, "process", false);
         ProblemTask::showCentralList(0, "todo", false);
      }

      echo "</td></tr>";
      echo "</table></td>";
      echo "<td class='top'  width='50%'><table class='central'>";
      echo "<tr class='noHover'><td>";
      Planning::showCentral(Session::getLoginUserID());


      //REMINDER PERSONAL
      Reminder::showListForCentral();

      //REMINDER PUBLIC
       /*
      if (Session::haveRight("reminder_public", READ)) {
         Reminder::showListForCentral(false);
      }*/
      echo "</td></tr>";
      echo "</table></td></tr></table>";
   }


   /**
    * Show the central RSS view
    *
    * @since version 0.84
   **/
   /*static function showRSSView() {

      echo "<table class='tab_cadre_central'>";

      echo "<tr class='noHover'><td class='top' width='50%'>";
      RSSFeed::showListForCentral();
      echo "</td><td class='top' width='50%'>";
      if (RSSFeed::canView()) {
         RSSFeed::showListForCentral(false);
      } else {
         echo "&nbsp;";
      }
      echo "</td></tr>";
      echo "</table>";
   }*/


   /**
    * Show the central group view
   **/
   /*static function showGroupView() {

      $showticket = Session::haveRightsOr("ticket", [Ticket::READALL, Ticket::READASSIGN]);

      $showproblem = Session::haveRightsOr('problem', [Problem::READALL, Problem::READMY]);

      echo "<table class='tab_cadre_central'>";
      echo "<tr class='noHover'><td class='top' width='50%'><table class='central'>";
      echo "<tr class='noHover'><td>";
      if ($showticket) {
         Ticket::showCentralList(0, "process", true);
         TicketTask::showCentralList(0, "todo", true);
      }
      if (Session::haveRight('ticket', Ticket::READGROUP)) {
         Ticket::showCentralList(0, "waiting", true);
      }
      if ($showproblem) {
         Problem::showCentralList(0, "process", true);
         ProblemTask::showCentralList(0, "todo", true);
      }

      echo "</td></tr>";
      echo "</table></td>";
      echo "<td class='top' width='50%'><table class='central'>";
      echo "<tr class='noHover'><td>";
      if (Session::haveRight('ticket', Ticket::READGROUP)) {
         Ticket::showCentralList(0, "observed", true);
         Ticket::showCentralList(0, "toapprove", true);
         Ticket::showCentralList(0, "requestbyself", true);
      } else {
         Ticket::showCentralList(0, "waiting", true);
      }
      echo "</td></tr>";
      echo "</table></td></tr></table>";
   }*/

}
