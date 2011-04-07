<?php
 
// No direct access
 
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.utilities.date');

$config =& JFactory::getConfig();
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//RaidPlanner//NONSGML v1.0//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:<?php echo $config->getValue( 'config.sitename' ); ?>

X-WR-TIMEZONE:Europe/London
X-ORIGINAL-URL:<?php echo JURI::base() . JRoute::_('index.php'); ?>

X-WR-CALDESC:<?php echo $config->getValue( 'config.sitename' ); ?> raidplanner
<?php foreach ($this->events as $event):?>
<?php if ($event[0]->raid_id):?>
BEGIN:VEVENT
UID:RPEVENTID<?php echo $event[0]->raid_id;?>

DTSTAMP;TZID=Europe/London:<?php echo JHTML::_('date',$event[0]->start_time,'%Y%m%dT%H%M%S',0);?>

ORGANIZER:<?php echo $event[0]->raid_leader;?>

DTSTART;TZID=Europe/London:<?php echo JHTML::_('date',$event[0]->start_time,'%Y%m%dT%H%M%S',0);?>

DTEND;TZID=Europe/London:<?php echo JHTML::_('date',$event[0]->end_time,'%Y%m%dT%H%M%S',0);?>

SUMMARY:<?php echo $event[0]->location;?>

DESCRIPTION:<?php echo $event[0]->description;?>

URL:<?php echo trim(JURI::base(),'/') . JRoute::_('index.php?option=com_raidplanner&view=calendar&task=default&modalevent='.$event[0]->raid_id.'');?>

BEGIN:VALARM
ACTION:AUDIO
TRIGGER;TZID=Europe/London:<?php echo JHTML::_('date',$event[0]->invite_time,'%Y%m%dT%H%M%S',0);?>

REPEAT:1
END:VALARM
END:VEVENT
<?php endif; ?>
<?php endforeach; ?>
END:VCALENDAR
