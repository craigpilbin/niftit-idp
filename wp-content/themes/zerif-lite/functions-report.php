<?php

// Function will get all the attendees per city or total attendee if $bool is set to false;
function getAttendeePerCity($eventID, $value, $bool) {

    if($bool):
      $args = array(
                        'relation' => 'AND',
                        array(
                          'key' => 'attended_at',
                          'value' => NULL,
                          'compare' => '!='
                        ),
                        array(
                          'key' => 'event_id',
                          'value' => $eventID,
                          'compare' => 'LIKE'
                        ),
                        array(
                          'key' => 'attended',
                          'value' => '"' . $value . '"',
                          'compare' => 'LIKE'
                        )
            );
    else:
      $args = array(
                        'relation' => 'AND',
                        array(
                          'key' => 'attended_at',
                          'value' => NULL,
                          'compare' => '!='
                        ),
                        array(
                          'key' => 'event_id',
                          'value' => $eventID,
                          'compare' => 'LIKE'
                        )
              );
    endif;


    $attendees = get_posts(array(
            'posts_per_page' => -1,
            'post_type'      => 'registrees',
            'meta_query' => $args
        ));
    return count($attendees);
}


function export_attendees(){

  $args = array(
            'relation' => 'AND',
            array(
              'key' => 'attended_at',
              'value' => NULL,
              'compare' => '!='
            ),
            array(
              'key' => 'event_id',
              'value' => $_POST['eventID'],
              'compare' => 'LIKE'
            )
  );

  $users = get_posts(array(
      'posts_per_page' => -1,
      'post_type'      => 'registrees',
      'meta_query' => $args
  ));

  $html = '<table id="user-notes">';

  $html .= '<tr>
          <th>idp</th>
          <th>Full Name</th>
          <th>DOB</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Alt Email</th>
          <th>Attended At</th>
          <th>Attended</th>
          <th>Nearest IDP</th>
          <th>District/Province</th>
          <th>Destination</th>
          <th>Counsellor</th>
          <th>Oscar ID</th>
          <th>GPA</th>
          <th>English Level</th>
          <th>Current Major/Year</th>
          <th>Current School/University</th>
          <th>Interested Level of Study</th>
          <th>Interested Major</th>
          <th>Intake (Year)</th>
          <th>Intake (Month)</th>
          <th>Status</th>
          <th>Lead N/O</th>
        </tr>';

  foreach($users as $u){

    $a = get_post_meta($u->ID)['user'][0];

    $user = get_user_by('id', $a);

    $usermeta = get_user_meta($a);

    $html .= '<tr>';
    $html .= '<td>' . $user->ID . '</td>';

    if($usermeta['alt_name'][0]) :
      $html .= '<td>' . stripVN($usermeta['alt_name'][0]) . '</td>';
    else :
      $html .= '<td>' . stripVN($user->display_name) . '</td>';
    endif;

    $html .= '<td>' . $usermeta['dob'][0] . '</td>';
    $html .= '<td>' . $usermeta['phone'][0] . '</td>';
    $html .= '<td>' . $user->user_email . '</td>';
    $html .= '<td>' . $usermeta['alt_email'][0] . '</td>';
    $html .= '<td>' . get_post_meta($u->ID)['attended_at'][0] . '</td>';
    $html .= '<td>' . get_field('attended', $u->ID)[0]->post_title . '</td>';
    $html .= '<td>' . stripVN($usermeta['nearest_IDP'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['district'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['destination'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['counsellor'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['oscar_id'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['gpa'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['english_level'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['current_major'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['current_university'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['interest_level_study'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['interested_major'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['intake_year'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['intake_month'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['status'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['lead'][0]) . '</td>';

    $html .= '</tr>';

  }

  $html .= '</table>';

  echo $html;

  die();

}
add_action( 'wp_ajax_nopriv_export_attendees', 'export_attendees' );
add_action( 'wp_ajax_export_attendees', 'export_attendees' );


function export_report_b(){

  $rows = [];

  $institutions = get_users(array(
                    'role' => 'Author'
                  ));


  foreach ($institutions as $inst) {

    $args = array(
              'relation' => 'AND',
              array(
                'key' => 'event_id',
                'value' => $_POST['eventID'],
                'compare' => 'LIKE'
              ),
              array(
                'key' => 'institutiion_id',
                'value' => $inst->ID,
                'compare' => 'LIKE'
              )
    );

    $notes = get_posts(array(
      'posts_per_page' => -1,
      'post_type'      => 'notes',
      'meta_query' => $args
    ));

    foreach ($notes as $n) {

      // SETUP OUR ARRAY
      if(get_field('institutiion_id', $n->ID)) {
        $temp = array(
          'i_id'  => get_field('institutiion_id', $n->ID)['ID'],
          'u_id'  => get_field('attendee_id', $n->ID)['ID'],
          'n_id'  => $n->ID
        );

        array_push($rows, $temp);
      }
    }

  }

  function cmp($a, $b) {
    return $a["i_id"] - $b["i_id"];
  }

  usort($rows, "cmp");
  echo $rows[0]['u_id'];

  $html = '<table id="user-notes">';

  $html .= '<tr>
          <th>institution Name</th>
          <th>idp</th>
          <th>Full Name</th>
          <th>DOB</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Alt Email</th>
          <th>Attended At</th>
          <th>Attended</th>
          <th>Nearest IDP</th>
          <th>District/Province</th>
          <th>Destination</th>
          <th>Counsellor</th>
          <th>Oscar ID</th>
          <th>GPA</th>
          <th>English Level</th>
          <th>Current Major/Year</th>
          <th>Current School/University</th>
          <th>Interested Level of Study</th>
          <th>Interested Major</th>
          <th>Intake (Year)</th>
          <th>Intake (Month)</th>
          <th>Status</th>
          <th>Lead N/O</th>
          <th>Telesales Note</th>
          <th>Institution Note</th>
        </tr>';

  foreach($rows as $u){

    $user = get_user_by('id', $u['u_id']);

    $usermeta = get_user_meta($u['u_id']);

    $inst = get_user_by('id', $u['i_id']);

    $this_note = stripVN(get_post_meta($u['n_id'])['notes'][0]);
    $this_note = strip_tags($this_note);
    $this_note = str_replace(array("\r\n", "\r"), "\n", $this_note);
    $lines = explode("\n", $this_note);
    $new_lines = array();

    foreach ($lines as $i => $line) {
        if(!empty($line))
            $new_lines[] = trim($line);
    }
    $this_note = implode($new_lines);

    $tele_note = stripVN($usermeta['telesale_note'][0]);
    $tele_note = strip_tags($tele_note);
    $tele_note = str_replace(array("\r\n", "\r"), "\n", $tele_note);
    $lines = explode("\n", $tele_note);
    $new_lines = array();

    foreach ($lines as $i => $line) {
        if(!empty($line))
            $new_lines[] = trim($line);
    }
    $tele_note = implode($new_lines);

    $html .= '<tr>';

    $html .= '<td>' . stripVN($inst->display_name) . '</td>';
    $html .= '<td>' . $user->ID . '</td>';

    if($usermeta['alt_name'][0]) :
      $html .= '<td>' . stripVN($usermeta['alt_name'][0]) . '</td>';
    else :
      $html .= '<td>' . stripVN($user->display_name) . '</td>';
    endif;

    $html .= '<td>' . $usermeta['dob'][0] . '</td>';
    $html .= '<td>' . $usermeta['phone'][0] . '</td>';
    $html .= '<td>' . $user->user_email . '</td>';
    $html .= '<td>' . $usermeta['alt_email'][0] . '</td>';
    $html .= '<td>' . get_post_meta($u->ID)['attended_at'][0] . '</td>';
    $html .= '<td>' . get_field('attended', $u->ID)[0]->post_title . '</td>';
    $html .= '<td>' . stripVN($usermeta['nearest_IDP'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['district'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['destination'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['counsellor'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['oscar_id'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['gpa'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['english_level'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['current_major'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['current_university'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['interest_level_study'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['interested_major'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['intake_year'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['intake_month'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['status'][0]) . '</td>';
    $html .= '<td>' . stripVN($usermeta['lead'][0]) . '</td>';
    $html .= '<td>' . $tele_note . '</td>';
    $html .= '<td>' . $this_note . '</td>';

    $html .= '</tr>';

  }

  $html .= '</table>';

  echo $html;

  die();

}
add_action( 'wp_ajax_nopriv_export_report_b', 'export_report_b' );
add_action( 'wp_ajax_export_report_b', 'export_report_b' );


function getInterviewsperCity($eventID, $instituionID) {
    $oneseventythree = 0;
    $oneseventyfour = 0;
    $oneseventyfive = 0;
    $oneseventysix = 0;
    $zero = 0;

    $args = array(
                      'relation' => 'AND',
                      array(
                        'key' => 'event_id',
                        'value' => $eventID,
                        'compare' => 'LIKE'
                      ),
                      array(
                        'key' => 'institutiion_id',
                        'value' => $instituionID,
                        'compare' => 'LIKE'
                      )
    );

  $notes = get_posts(array(
          'posts_per_page' => -1,
          'post_type'      => 'notes',
          'meta_query' => $args
      ));

  foreach($notes as $n):
    $data = get_post_meta($n->ID)['attendee_id'];
    switch (inventoryCity($data[0])) {
      case 173:
        $oneseventythree++;
        break;
      case 174:
        $oneseventyfour++;
        break;
      case 175:
        $oneseventyfive++;
        break;
      case 176:
        $oneseventysix++;
        break;
      case 0:
        $zero++;
        break;
    }
  endforeach;

  echo '<td>'.$oneseventythree.'</td>';
  echo '<td>'.$oneseventyfour.'</td>';
  echo '<td>'.$oneseventyfive.'</td>';
  echo '<td>'.$oneseventysix.'</td>';
  echo '<td>'.$zero.'</td>';
}

//Count for an attendee which city they are coming from
function inventoryCity($value) {
  $args = array(
                    array(
                      'key' => 'user',
                      'value' => $value,
                      'compare' => 'LIKE'
                    )
  );

  $attendee = get_posts(array(
          'posts_per_page' => -1,
          'post_type'      => 'registrees',
          'meta_query' => $args
      ));

  $city = get_post_meta($attendee[0]->ID)['attended'][0];


  switch ($city) {
    case 'a:1:{i:0;s:3:"173";}':
      return 173;
      break;
    case 'a:1:{i:0;s:3:"174";}':
      return 174;
      break;
    case 'a:1:{i:0;s:3:"175";}':
      return 175;
      break;
    case 'a:1:{i:0;s:3:"176";}':
      return 176;
      break;
    default:
      return 0;
      break;
  }

}






function getInterviewsTotal($eventID, $instituionID, $bool) {

  //set to False
  if(!$bool):
    $args = array(
                      array(
                        'key' => 'event_id',
                        'value' => $eventID,
                        'compare' => 'LIKE'
                      )
    );
  else:
    $args = array(
                      'relation' => 'AND',
                      array(
                        'key' => 'event_id',
                        'value' => $eventID,
                        'compare' => 'LIKE'
                      ),
                      array(
                        'key' => 'institutiion_id',
                        'value' => $instituionID,
                        'compare' => 'LIKE'
                      )
    );
  endif;

  $notes = get_posts(array(
          'posts_per_page' => -1,
          'post_type'      => 'notes',
          'meta_query' => $args
      ));

  return count($notes);
}

function getInterviewsCountPerInstitutions($eventID) {
  // Will get all the institution
  $institutions = get_users(array(
                    'role' => 'Author'
                  ));

  echo '<table class="table">';
    echo '<thead>
            <tr>
              <td>Institution</td>
              <td>Total Comments</td>
              <td>Can Tho</td>
              <td>Da Nang</td>
              <td>Hanoi</td>
              <td>Ho Chi Minh</td>
              <td>Unclassified</td>
            </tr>
          </thead>
          <tbody>';
  foreach($institutions as $i):
    echo '<tr>';

    echo '<td class="col-lg-5">' . $i->display_name . '</td>';

    echo '<td class="col-lg-1"><strong>' . getInterviewsTotal($eventID, $i->ID, true) . '</strong></td>';

    getInterviewsperCity($eventID, $i->ID);

    echo '<td class="col-lg-2"><a href="/admin-report-detail/?eventID='.$eventID.'&InstitutionID='.$i->ID.'" event-data="' .$eventID. '" inst-data="' .$i->ID. '" class="btn btn-primary export-table-btn" target="_blank">View</a></td>';

    echo'</tr>';

  endforeach;

  echo'</tbody></table>';
}

function getInterviewsPerInstitutions($eventID, $instituionID) {
  // Will get all the institution
  $args = array(
                    'relation' => 'AND',
                    array(
                      'key' => 'event_id',
                      'value' => $eventID,
                      'compare' => 'LIKE'
                    ),
                    array(
                      'key' => 'institutiion_id',
                      'value' => $instituionID,
                      'compare' => 'LIKE'
                    )
  );

  $notes = get_posts(array(
        'posts_per_page' => -1,
        'post_type'      => 'notes',
        'meta_query' => $args
    ));

  echo '<table id="user-notes">';

  echo '<tr>
          <th>idp</th>
          <th>Full Name</th>
          <th>DOB</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Current Level of study</th>
          <th>Interest Level of Study</th>
          <th>Interest Major</th>
          <th>Attended At</th>
          <th>Notes</th>
          <th>Last Modified</th>
        </tr>';


  foreach($notes as $n):
    $uid =  $n->attendee_id;
    $user = get_user_by('ID', $uid);
    $usermeta = get_user_meta($uid);

    $username = ($user->display_name)?$user->display_name:$user->user_login;

    echo '<tr>';
    echo '<td>' . $user->ID . '</td>';
    echo '<td>' . stripVN($username) . '</td>';
    echo '<td>' . $usermeta['dob'][0] . '</td>';
    echo '<td>' . $usermeta['phone'][0] . '</td>';
    echo '<td>' . $user->user_email . '</td>';
    echo '<td>' . $usermeta['current_unversity'][0]. '</td>';
    echo '<td>' . $usermeta['interest_level_study'][0] . '</td>';
    echo '<td>' . $usermeta['interested_major'][0] . '</td>';
    echo '<td>' . getAttendanceforUserID($user->ID, $_GET['eventID'] ) . '</td>';
    echo '<td><div>'.preg_replace('/\s+/', ' ', $n->notes).'</div></td>';
    echo '<td>date modified: '.$n->post_modified.'</td>';
    echo '</tr>';
  endforeach;

  echo '</table>';
}

function export_table(){

  // Will get all the institution
  $args = array(
                    'relation' => 'AND',
                    array(
                      'key' => 'event_id',
                      'value' => $_POST['eventID'],
                      'compare' => 'LIKE'
                    ),
                    array(
                      'key' => 'institutiion_id',
                      'value' => $_POST['instituionID'],
                      'compare' => 'LIKE'
                    )
  );

  $notes = get_posts(array(
        'posts_per_page' => -1,
        'post_type'      => 'notes',
        'meta_query' => $args
    ));

  $html = '<table id="user-notes">';

  $html .= '<tr>
          <th>idp</th>
          <th>Full Name</th>
          <th>DOB</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Current Level of study</th>
          <th>Interest Level of Study</th>
          <th>Interest Major</th>
		  <th>Attended At</th>
          <th>Notes</th>
          <th>Last Modified</th>
        </tr>';


  foreach($notes as $n):
    $uid =  $n->attendee_id;
    $user = get_user_by('ID', $uid);
    $usermeta = get_user_meta($uid);

    $username = ($user->display_name)?$user->display_name:$user->user_login;

    $html .= '<tr>';
    $html .= '<td>' . $user->ID . '</td>';
    $html .= '<td>' . stripVN($username) . '</td>';
    $html .= '<td>' . $usermeta['dob'][0] . '</td>';
    $html .= '<td>' . $usermeta['phone'][0] . '</td>';
    $html .= '<td>' . $user->user_email . '</td>';
    $html .= '<td>' . $usermeta['current_unversity'][0]. '</td>';
    $html .= '<td>' . $usermeta['interest_level_study'][0] . '</td>';
    $html .= '<td>' . $usermeta['interested_major'][0] . '</td>';
	$html .= '<td>' . getAttendanceforUserID($user->ID, $_GET['eventID'] ) . '</td>';
    $html .= '<td><div>'.preg_replace('/\s+/', ' ', $n->notes).'</div></td>';
    $html .= '<td>date modified: '.$n->post_modified.'</td>';
    $html .= '</tr>';
  endforeach;

  $html .= '</table>';

  echo $html;

  die();

}
add_action( 'wp_ajax_nopriv_export_table', 'export_table' );
add_action( 'wp_ajax_export_table', 'export_table' );

function removePosts() {
	//wp_delete_post($id);
	  $notes = get_posts(array(
          'posts_per_page' => -1,
          'post_type'      => 'notes',
          'meta_query' => $args
      ));

	  $args = array(
                    array(
                      'key' => 'attendee_id',
                      'value' => '1',
                      'compare' => 'LIKE'
                    )
		);

		foreach($notes as $n):
			//var_dump(get_post_meta($n->ID));

			echo $n->attendee_id;

			if(strlen($n->attendee_id) > 4) {

				$temp = substr($n->attendee_id, 0, 4);

				$attendee_id = get_acf_key('attendee_id');
				//update_field( $attendee_id, $temp, $n->ID );



			}

			/*if($n->attendee_id == 1717):
				echo $n->ID;
				echo '</br>';
			endif;*/

		endforeach;
}


function getAttendanceforUserID($userID, $eventID) {
  $args = array(
                    'relation' => 'AND',
                    array(
                      'key' => 'event_id',
                      'value' => $eventID, //$eventID,
                      'compare' => 'LIKE'
                    ),
                    array(
                      'key' => 'user',
                      'value' => $userID, //$eventID,
                      'compare' => 'LIKE'
                    )
  );
  $attendance = get_posts(array(
                  'post_type' => 'registrees',
                  'meta_query' => $args
  ));

  $attendanceID = $attendance[0]->ID;

  $attended = get_post_meta($attendanceID)['attended'];

  $cities = '';

  foreach($attended as $a):
    switch ($a) {
      case strpos($a, '173') == true:
        $cities .= 'Can Tho';
        break;
      case strpos($a, '174') == true:
        $cities .= 'Da Nang';
        break;
      case strpos($a, '175') == true:
        $cities .= 'Hanoi';
        break;
      case strpos($a, '176') == true:
        $cities .= 'Ho Chi Minh City';
        break;

    }
  endforeach;

  return $cities;


}

?>
