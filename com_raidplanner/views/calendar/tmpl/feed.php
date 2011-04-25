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

$config =& JFactory::getConfig();
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//RaidPlanner//NONSGML v1.0//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:<?php echo $config->getValue( 'config.sitename' ); ?>

X-WR-TIMEZONE:<?php echo $this->tzname; ?>

X-ORIGINAL-URL:<?php echo JURI::base() . JRoute::_('index.php'); ?>

X-WR-CALDESC:<?php echo $config->getValue( 'config.sitename' ); ?> raidplanner
<?php foreach ($this->events as $event):?>
<?php if ($event[0]->raid_id):?>
BEGIN:VEVENT
UID:RPEVENTID<?php echo $event[0]->raid_id;?>

DTSTAMP;TZID=<?php echo $this->tzname; ?>:<?php echo JHTML::_('date',$event[0]->start_time,'%Y%m%dT%H%M%S', $this->tzoffset);?>

ORGANIZER:<?php echo $event[0]->raid_leader;?>

DTSTART;TZID=<?php echo $this->tzname; ?>:<?php echo JHTML::_('date',$event[0]->start_time,'%Y%m%dT%H%M%S', $this->tzoffset);?>

DTEND;TZID=<?php echo $this->tzname; ?>:<?php echo JHTML::_('date',$event[0]->end_time,'%Y%m%dT%H%M%S', $this->tzoffset);?>

SUMMARY:<?php echo $event[0]->location;?>

DESCRIPTION:<?php echo $event[0]->description;?>

URL:<?php echo trim(JURI::base(),'/') . JRoute::_('index.php?option=com_raidplanner&view=calendar&task=default&modalevent='.$event[0]->raid_id.'');?>

BEGIN:VALARM
ACTION:AUDIO
TRIGGER;TZID=<?php echo $this->tzname; ?>:<?php echo JHTML::_('date',$event[0]->invite_time,'%Y%m%dT%H%M%S', $this->tzoffset);?>

REPEAT:1
END:VALARM
END:VEVENT
<?php endif; ?>
<?php endforeach; ?>
END:VCALENDAR
