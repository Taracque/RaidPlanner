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

X-WR-TIMEZONE:GMT
X-ORIGINAL-URL:<?php echo JURI::base() . JRoute::_('index.php'); ?>

X-WR-CALDESC:<?php echo $config->getValue( 'config.sitename' ); ?> raidplanner
BEGIN:VTIMEZONE
TZID:GMT
END:VTIMEZONE
<?php foreach ($this->events as $event):?>
BEGIN:VEVENT
UID:RPEVENTID<?php echo $event[0]->raid_id;?>

DTSTAMP:<?php echo JHTML::_('date',$event[0]->start_time,'%Y%m%dT%H%M%SZ',0);?>

ORGANIZER:<?php echo $event[0]->raid_leader;?>

DTSTART:<?php echo JHTML::_('date',$event[0]->start_time,'%Y%m%dT%H%M%SZ',0);?>

DTEND:<?php echo JHTML::_('date',$event[0]->start_time,'%Y%m%dT%H%M%SZ',1);?>

SUMMARY:<?php echo $event[0]->location;?>

DESCRIPTION:<?php echo $event[0]->description;?>

URL:<?php echo trim(JURI::base(),'/') . JRoute::_('index.php?option=com_raidplanner&view=calendar&task=default&modalevent='.$event[0]->raid_id.'');?>

BEGIN:VALARM
ACTION:AUDIO
TRIGGER:<?php echo JHTML::_('date',$event[0]->invite_time,'%Y%m%dT%H%M%SZ',0);?>

REPEAT:1
END:VALARM
END:VEVENT
<?php endforeach; ?>
END:VCALENDAR
