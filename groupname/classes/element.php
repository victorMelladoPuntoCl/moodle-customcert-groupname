<?php
// This file is part of the customcert module for Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file contains the customcert element studentname's core interaction API.
 *
 * @package    customcertelement_studentname
 * @copyright  2013 Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 

namespace customcertelement_groupname;

defined('MOODLE_INTERNAL') || die();

/**
 * The customcert element studentname's core interaction API.
 *
 * @package    customcertelement_studentname
 * @copyright  2013 Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class element extends \mod_customcert\element {
		
    /**
     * Handles rendering the element on the pdf.
     *
     * @param \pdf $pdf the pdf object
     * @param bool $preview true if it is a preview, false otherwise
     * @param \stdClass $user the user we are rendering this for
     */
    public function render($pdf, $preview, $user) {
		global $DB;
				
		$courseid = \mod_customcert\element_helper::get_courseid($this->id);
		$userid = $user -> id;
		$groups = array();
		
		$sql = "SELECT id, name FROM {groups} WHERE courseid = ?";
		
		$result = $DB->get_records_sql($sql, array($courseid));
		
		foreach ($result as $item) {
          array_push($groups, ["id" => $item -> id, "name" => $item -> name] );		  
		}
		
		$sql2 = "SELECT id FROM {groups_members} WHERE groupid = ? AND userid = ?";
		
		foreach ($groups as $item) {
			$groupId = $item["id"];			
			$checkGroup = $DB->record_exists_sql($sql2, array($groupId,$userid));
			
			if($checkGroup){
				$groupName .= $item["name"]."<br />";
			}
		}
										
		\mod_customcert\element_helper::render_content($pdf, $this, $groupName);	
    }

    /**
     * Render the element in html.
     *
     * This function is used to render the element when we are using the
     * drag and drop interface to position it.
     *
     * @return string the html
     */
    public function render_html() {

        return \mod_customcert\element_helper::render_html_content($this, "-- Groupname --" );
    }
	
	
}
