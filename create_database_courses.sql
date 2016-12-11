create database courses;
grant select,insert,update,delete on courses.* to 'tchr205iyw' identified by 'liraw57tqz'; 
GRANT SELECT,INSERT,UPDATE,DELETE on `courses`.`authents` TO 'usernm33' identified BY 'psw2047i';
use courses;
create table `cpecialitys`
(
	`id` int(20) unsigned not null auto_increment primary key,
	`title` varchar(220),
	`priseBasis` decimal,
	`description` text,
	`quantity` int default '0',
	`bossId` int unsigned,
	`work`  int(1)
);

create table `students`
(
	`id` int(20) unsigned not null auto_increment primary key,
	`name` varchar(100),
	`surname` varchar(100),
	`birthday` date,
	`telefon` varchar(50),
	`adress` varchar(220),
	`email` varchar(100),
	`skype` varchar(100),
	`characteristic` text,
	`dogovor` int,
	`work`  int(1)
);
create table `groups`
(
	`id` int(20) unsigned not null auto_increment primary key,
	`id_special` int(20) unsigned,
	`title` varchar(220),
	`price` decimal,
	`periodicity` varchar(20),
	`quantity` int(20)  default '0',
	`duration` int(20)  default '0',
	`bossId` int(20) unsigned,
	`start_data_plan` date,
	`start_data_fact` date,
	`end_data_plan` date,
	`end_data_fact` date,
	`status` enum('anons','work','archiv'),
	`numLesson` int(10)	
);
create table `auditorias`
(
	`id` int(20) unsigned not null auto_increment primary key,
	`title` varchar(220),
	`adress` varchar(220),
	`description` text,
	`work`  int(1)
);
create table `groups_list`
(
	`id_group` int(20) unsigned,
	`id_student` int(20) unsigned,
	`receipt_data` date,
	`expulsion_data` date,
	`status` enum('student','graduate','expulsion')
);
create table `temetable`
(
	`id` int(20) unsigned not null auto_increment primary key,
	`id_group` int(20) unsigned,
	`id_auditorias` int unsigned,
	`dataPlan` date,
	`dataFact` date,
	`timePlan` time,
	`timeFact` time,
	`duration` int(20),
	`id_teacherPlan` int(20) unsigned,
	`id_teacherFact` int(20) unsigned,
	`theme` text,
	`status` enum('plan','fact')	
);
create table `register`
(
	`id_temetable` int(20) unsigned,
	`id_group` int(20) unsigned,
	`id_student` int(20) unsigned,
	`attendance` int(20),
	`assesment` int(20),
	`homework` text,
	`remarks` text	
);
create table `teacher`
(
	`id` int(20) unsigned not null auto_increment primary key,
	`name` varchar(100),
	`surname` varchar(100),
	`birthday` date,
	`telefon` varchar(50),
	`adress` varchar(220),
	`email` varchar(100),
	`skype` varchar(100),
	`status`  varchar(50),
	`work`  int(1)
);
create table `authents`
(
	`id` int(20) unsigned not null auto_increment primary key,
	`login` varchar(120),
	`password` varchar(120),
	`status` varchar(20),
	`username` varchar(120),
	`id_staff` int(20) unsigned
);
insert into `authents` values ( NULL, '2faa262afd91216537769bc68fdde4a413056653a1c3e7f133ad0e9244508e1a','2faa262afd91216537769bc68fdde4a413056653a1c3e7f133ad0e9244508e1a','manager','jonn',NULL);