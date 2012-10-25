<?php
/*------------------------------------------------------------------------
# Feed Template for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.utilities.date');

$config = JFactory::getConfig()->toArray();
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//RaidPlanner//NONSGML v1.0//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:<?php echo $config['sitename']; ?>

X-WR-TIMEZONE:<?php echo $this->tzname; ?>

X-ORIGINAL-URL:<?php echo JURI::base() . JRoute::_('index.php'); ?>

X-WR-CALDESC:<?php echo $config['sitename']; ?> RaidPlanner
<?php if (class_exists('DateTimeZone')) : ?>
BEGIN:VTIMEZONE
TZID:<?php echo $this->tzname; ?>
<?php
	$timezone = new DateTimeZone( $this->tzname );
	$transitions = $timezone->getTransitions();
?>
<?php foreach($transitions as $tridx => $transition) :?>
<?php if($tridx>0) : ?>

BEGIN:<?php echo ($transition['isdst']==1)?'DAYLIGHT':'STANDARD';?>

TZOFFSETFROM:<?php printf('%+05d', ($transitions[$tridx - 1]['offset']/36) ); ?>

RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU
DTSTART:<?php echo str_replace(array('-',':'),'',substr($transition['time'],0,-5)); ?>

TZNAME:<?php echo $transition['abbr'];?>

TZOFFSETTO:<?php printf('%+05d', ($transition['offset']/36) ); ?>

END:<?php echo ($transition['isdst']==1)?'DAYLIGHT':'STANDARD'; ?>
<?php endif;
endforeach; ?>

END:VTIMEZONE
<?php endif; ?>
<?php foreach ($this->events as $event):?>
<?php if ($event[0]->raid_id):?>
BEGIN:VEVENT
UID:RPEVENTID<?php echo $event[0]->raid_id;?>

DTSTAMP;TZID=<?php echo $this->tzname; ?>:<?php echo RaidPlannerHelper::getDate($event[0]->start_time, $this->tzoffset, $this->dateformat);?>

ORGANIZER:<?php echo $event[0]->raid_leader;?>

DTSTART;TZID=<?php echo $this->tzname; ?>:<?php echo RaidPlannerHelper::getDate($event[0]->start_time, $this->tzoffset, $this->dateformat);?>

DTEND;TZID=<?php echo $this->tzname; ?>:<?php echo RaidPlannerHelper::getDate($event[0]->end_time, $this->tzoffset, $this->dateformat);?>

SUMMARY:<?php echo $event[0]->location;?>

DESCRIPTION:<?php echo $event[0]->description;?>

URL:<?php echo trim(JURI::base(),'/') . JRoute::_('index.php?option=com_raidplanner&view=calendar&modalevent='.$event[0]->raid_id.'');?>

BEGIN:VALARM
ACTION:AUDIO
TRIGGER;TZID=<?php echo $this->tzname; ?>:<?php echo RaidPlannerHelper::getDate($event[0]->invite_time, $this->tzoffset, $this->dateformat);?>

REPEAT:1
END:VALARM
END:VEVENT
<?php endif; ?>
<?php endforeach; ?>
END:VCALENDAR
