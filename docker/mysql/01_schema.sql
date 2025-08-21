--
-- Database: `otcsystems`
--

-- --------------------------------------------------------

--
-- Table structure for table `accesses`
--

CREATE TABLE `accesses` (
  `aid` int(2) NOT NULL,
  `aname` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accesses_pages`
--

CREATE TABLE `accesses_pages` (
  `ap_id` int(10) NOT NULL,
  `page_id` int(6) NOT NULL,
  `aid` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `aid` int(10) NOT NULL,
  `fr_dtime` datetime NOT NULL,
  `to_dtime` datetime NOT NULL,
  `aname` varchar(100) NOT NULL,
  `adesc` varchar(150) NOT NULL,
  `venue` varchar(150) NOT NULL,
  `did` int(2) NOT NULL COMMENT 'department concerned',
  `remarks` varchar(150) NOT NULL,
  `last_update_id` int(4) NOT NULL,
  `last_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activities_people`
--

CREATE TABLE `activities_people` (
  `apid` int(10) NOT NULL,
  `aid` int(10) NOT NULL,
  `eid` int(11) NOT NULL COMMENT 'employee'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agencies`
--

CREATE TABLE `agencies` (
  `agency_id` int(2) NOT NULL,
  `agency_name` char(75) NOT NULL,
  `active` int(1) NOT NULL COMMENT '1 yes, 0 no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `aid` int(10) NOT NULL,
  `adtime` datetime NOT NULL,
  `aname` varchar(100) NOT NULL,
  `adesc` varchar(150) NOT NULL,
  `did` int(2) NOT NULL COMMENT 'department concerned',
  `remarks` varchar(150) NOT NULL,
  `last_update_id` int(4) NOT NULL,
  `last_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `aid` int(10) NOT NULL,
  `eid` int(4) NOT NULL,
  `adate` date NOT NULL,
  `am_in` time NOT NULL,
  `am_out` time NOT NULL,
  `pm_in` time NOT NULL,
  `pm_out` time NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '-1 for checking, 1 present, 2 UA, 3 SL, 4 VL, 5 SPL, 6 Travel Order, 7 DMS',
  `tardiness` int(4) NOT NULL,
  `undertime` int(4) NOT NULL,
  `remarks` varchar(150) NOT NULL,
  `last_update_id` int(4) NOT NULL,
  `last_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brgys`
--

CREATE TABLE `brgys` (
  `bid` int(10) NOT NULL,
  `tid` int(6) NOT NULL,
  `bname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cid` int(2) NOT NULL,
  `cname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cgs_efiles`
--

CREATE TABLE `cgs_efiles` (
  `cfid` int(10) NOT NULL,
  `ccid` int(6) NOT NULL,
  `ctype` tinyint(1) NOT NULL COMMENT '1 letter, 2 finance, 3 OTC annual report, 4 cert of compliance, 5 authenticity of docs',
  `cfile` varchar(50) NOT NULL,
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cocs`
--

CREATE TABLE `cocs` (
  `cid` int(10) NOT NULL,
  `cnum` char(12) NOT NULL COMMENT 'control no, memo order no',
  `cdate` date NOT NULL,
  `eid` int(6) NOT NULL,
  `hrs_num` int(2) NOT NULL COMMENT 'coc earned',
  `exp_date` date NOT NULL,
  `hrs_bal` int(2) NOT NULL COMMENT 'coc balance',
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communications`
--

CREATE TABLE `communications` (
  `cid` int(10) NOT NULL,
  `ref_num` char(25) NOT NULL COMMENT 'YYYYMMXXXX',
  `source_ofc` varchar(75) NOT NULL COMMENT 'originating office',
  `source_person` char(75) NOT NULL,
  `source_position` char(75) NOT NULL,
  `receiver_ofc` char(75) NOT NULL,
  `receiver_person` char(75) NOT NULL,
  `receiver_position` char(75) NOT NULL,
  `ddate` date NOT NULL COMMENT 'date of document',
  `subject` varchar(300) NOT NULL,
  `remarks` varchar(250) NOT NULL,
  `rdate` date NOT NULL COMMENT 'received date',
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL,
  `direction` int(1) NOT NULL COMMENT '0 internal, 1 incoming, 2 out-going',
  `cmode` int(1) NOT NULL,
  `deliver_id` int(4) NOT NULL COMMENT 'employee id of delivery man',
  `sender_id` int(4) NOT NULL COMMENT 'employee id of sender - internal comm',
  `receiver_id` int(4) NOT NULL COMMENT 'employee id of receiver - internal comm'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communications_efiles`
--

CREATE TABLE `communications_efiles` (
  `cfid` int(10) NOT NULL,
  `cid` int(10) NOT NULL,
  `cfile` varchar(50) NOT NULL,
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives`
--

CREATE TABLE `cooperatives` (
  `cid` int(6) NOT NULL,
  `sid` int(2) NOT NULL COMMENT 'sector id',
  `tid` int(6) NOT NULL COMMENT 'town id',
  `ctype` tinyint(1) NOT NULL COMMENT '0 applicant, 1 registered, 2 accredited, 3 federation, 4 inactive',
  `cname` varchar(150) NOT NULL,
  `addr` varchar(150) NOT NULL,
  `chairman` char(40) NOT NULL,
  `chairman_num` varchar(75) NOT NULL,
  `contact_num` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `old_otc_num` varchar(40) NOT NULL,
  `old_otc_date` date NOT NULL,
  `new_otc_num` varchar(40) NOT NULL,
  `new_otc_date` date NOT NULL,
  `old_cda_num` varchar(40) NOT NULL,
  `old_cda_date` date NOT NULL,
  `new_cda_num` varchar(40) NOT NULL,
  `new_cda_date` date NOT NULL,
  `drivers_num` int(6) NOT NULL,
  `operators_num` int(6) NOT NULL,
  `workers_num` int(6) NOT NULL,
  `others_num` int(6) NOT NULL,
  `members_num` int(6) NOT NULL,
  `puj_num` int(6) NOT NULL,
  `mch_num` int(6) NOT NULL,
  `taxi_num` int(6) NOT NULL,
  `mb_num` int(6) NOT NULL,
  `bus_num` int(6) NOT NULL,
  `mcab_num` int(6) NOT NULL,
  `truck_num` int(6) NOT NULL,
  `tours_num` int(6) NOT NULL COMMENT 'tourist service',
  `uvx_num` int(6) NOT NULL COMMENT 'UV Express',
  `banka_num` int(6) NOT NULL,
  `class1_num` int(6) NOT NULL COMMENT 'PUVM multicab',
  `class2_num` int(6) NOT NULL COMMENT 'PUVM PUJ',
  `class3_num` int(6) NOT NULL COMMENT 'PUVM UVEx',
  `class4_num` int(6) NOT NULL COMMENT 'PUVM Cargo',
  `current_assets` decimal(18,2) NOT NULL,
  `fixed_assets` decimal(18,2) NOT NULL,
  `liabilities` decimal(18,2) NOT NULL,
  `equity` decimal(18,2) NOT NULL,
  `net_income` decimal(18,2) NOT NULL,
  `init_stock` decimal(15,2) NOT NULL COMMENT 'initial authorized capital stock',
  `present_stock` decimal(15,2) NOT NULL COMMENT 'present authorized capital stock',
  `subscribed` decimal(15,2) NOT NULL COMMENT 'subscribed capital',
  `paid_up` decimal(15,2) NOT NULL COMMENT 'paid-up capital',
  `scheme` char(50) NOT NULL COMMENT 'capital build up program scheme',
  `reserved` decimal(10,2) NOT NULL COMMENT 'reserved fund',
  `educ_training` decimal(10,2) NOT NULL COMMENT 'education and training',
  `comm_dev` decimal(10,2) NOT NULL COMMENT 'community development',
  `optional` decimal(10,2) NOT NULL COMMENT 'optional fund',
  `dividends` decimal(10,2) NOT NULL COMMENT 'distribution of dividends and patronage',
  `route` varchar(255) NOT NULL,
  `last_update_id` int(4) NOT NULL,
  `last_update` datetime NOT NULL,
  `last_verify_id` int(4) NOT NULL,
  `last_verify` datetime NOT NULL,
  `last_cgs` date NOT NULL,
  `cgs_expiry` date NOT NULL,
  `pa_expiry` date NOT NULL COMMENT 'date of expiration of provisional accreditation',
  `fed_id` int(6) NOT NULL COMMENT 'federation id'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_amendments`
--

CREATE TABLE `cooperatives_amendments` (
  `aid` int(6) NOT NULL,
  `cid` int(6) NOT NULL,
  `adate` date NOT NULL,
  `anum` char(50) NOT NULL,
  `adesc` char(150) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_backup`
--

CREATE TABLE `cooperatives_backup` (
  `cid` int(6) NOT NULL,
  `sid` int(2) NOT NULL COMMENT 'sector id',
  `tid` int(6) NOT NULL COMMENT 'town id',
  `ctype` tinyint(1) NOT NULL COMMENT '0 applicant, 1 registered, 2 accredited, 3 federation, 4 inactive',
  `cname` varchar(150) NOT NULL,
  `addr` varchar(150) NOT NULL,
  `chairman` char(40) NOT NULL,
  `chairman_num` varchar(75) NOT NULL,
  `contact_num` varchar(150) NOT NULL,
  `email` varchar(40) NOT NULL,
  `old_otc_num` varchar(40) NOT NULL,
  `old_otc_date` date NOT NULL,
  `new_otc_num` varchar(40) NOT NULL,
  `new_otc_date` date NOT NULL,
  `old_cda_num` varchar(40) NOT NULL,
  `old_cda_date` date NOT NULL,
  `new_cda_num` varchar(40) NOT NULL,
  `new_cda_date` date NOT NULL,
  `drivers_num` int(6) NOT NULL,
  `operators_num` int(6) NOT NULL,
  `workers_num` int(6) NOT NULL,
  `others_num` int(6) NOT NULL,
  `members_num` int(5) NOT NULL,
  `puj_num` int(5) NOT NULL,
  `mch_num` int(5) NOT NULL,
  `taxi_num` int(5) NOT NULL,
  `mb_num` int(5) NOT NULL,
  `bus_num` int(5) NOT NULL,
  `mcab_num` int(5) NOT NULL,
  `truck_num` int(5) NOT NULL,
  `auv_num` int(5) NOT NULL,
  `banka_num` int(5) NOT NULL,
  `current_assets` decimal(18,2) NOT NULL,
  `fixed_assets` decimal(18,2) NOT NULL,
  `liabilities` decimal(18,2) NOT NULL,
  `equity` decimal(18,2) NOT NULL,
  `net_income` decimal(18,2) NOT NULL,
  `init_stock` decimal(15,2) NOT NULL COMMENT 'initial authorized capital stock',
  `present_stock` decimal(15,2) NOT NULL COMMENT 'present authorized capital stock',
  `subscribed` decimal(15,2) NOT NULL COMMENT 'subscribed capital',
  `paid_up` decimal(15,2) NOT NULL COMMENT 'paid-up capital',
  `scheme` char(50) NOT NULL COMMENT 'capital build up program scheme',
  `reserved` decimal(10,2) NOT NULL COMMENT 'reserved fund',
  `educ_training` decimal(10,2) NOT NULL COMMENT 'education and training',
  `comm_dev` decimal(10,2) NOT NULL COMMENT 'community development',
  `optional` decimal(10,2) NOT NULL COMMENT 'optional fund',
  `dividends` decimal(10,2) NOT NULL COMMENT 'distribution of dividends and patronage',
  `route` varchar(255) NOT NULL,
  `last_update_id` int(4) NOT NULL,
  `last_update` datetime NOT NULL,
  `last_verify_id` int(4) NOT NULL,
  `last_verify` datetime NOT NULL,
  `last_cgs` date NOT NULL,
  `cgs_expiry` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_businesses`
--

CREATE TABLE `cooperatives_businesses` (
  `bid` int(10) NOT NULL,
  `cid` int(6) NOT NULL,
  `bdate` date NOT NULL,
  `bname` varchar(40) NOT NULL,
  `start_capital` decimal(12,2) NOT NULL,
  `capital_now` decimal(12,2) NOT NULL,
  `status` int(1) NOT NULL COMMENT '1 operational; 0 closed',
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_capitalizations`
--

CREATE TABLE `cooperatives_capitalizations` (
  `cfid` int(10) NOT NULL,
  `cid` int(6) NOT NULL,
  `cyear` year(4) NOT NULL,
  `init_stock` decimal(15,2) NOT NULL COMMENT 'initial authorized capital stock',
  `present_stock` decimal(15,2) NOT NULL COMMENT 'present authorized capital stock',
  `subscribed` decimal(15,2) NOT NULL COMMENT 'subscribed capital',
  `paid_up` decimal(15,2) NOT NULL COMMENT 'paid-up capital',
  `scheme` char(150) NOT NULL COMMENT 'capital build up program scheme',
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_cda`
--

CREATE TABLE `cooperatives_cda` (
  `ccid` int(6) NOT NULL,
  `cid` int(6) NOT NULL,
  `cda_num` varchar(20) NOT NULL,
  `cda_date` date NOT NULL,
  `cda_exp` date NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_cgs`
--

CREATE TABLE `cooperatives_cgs` (
  `ccid` int(6) NOT NULL,
  `cid` int(6) NOT NULL,
  `cgs_num` varchar(20) NOT NULL,
  `cgs_date` date NOT NULL,
  `cgs_exp` date NOT NULL,
  `last_update_id` int(4) NOT NULL,
  `last_update` datetime NOT NULL,
  `last_verify_id` int(4) NOT NULL,
  `last_verify` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_classes`
--

CREATE TABLE `cooperatives_classes` (
  `ccid` int(10) NOT NULL,
  `cid` int(6) NOT NULL,
  `cyear` year(4) NOT NULL,
  `cquarter` int(1) NOT NULL,
  `coperators` int(6) NOT NULL,
  `cdrivers` int(6) NOT NULL,
  `cworkers` int(6) NOT NULL,
  `ccommuters` int(6) NOT NULL,
  `cothers` int(6) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_donations`
--

CREATE TABLE `cooperatives_donations` (
  `did` int(6) NOT NULL,
  `cid` int(6) NOT NULL,
  `ddate` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `source` varchar(100) NOT NULL,
  `remarks` varchar(250) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_financials`
--

CREATE TABLE `cooperatives_financials` (
  `cfid` int(10) NOT NULL,
  `cid` int(6) NOT NULL,
  `cyear` year(4) NOT NULL,
  `current_assets` decimal(15,2) NOT NULL,
  `fixed_assets` decimal(15,2) NOT NULL,
  `liabilities` decimal(15,2) NOT NULL,
  `equity` decimal(15,2) NOT NULL,
  `net_income` decimal(10,2) NOT NULL,
  `cob` int(1) NOT NULL COMMENT '1 if yes, 0 otherwise',
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_governances`
--

CREATE TABLE `cooperatives_governances` (
  `gid` int(10) NOT NULL,
  `cid` int(10) NOT NULL,
  `gdate` date NOT NULL COMMENT 'date',
  `gplace` char(150) NOT NULL COMMENT 'place of meeting',
  `migs` int(5) NOT NULL COMMENT 'no. of members in good standing',
  `quorum` varchar(15) NOT NULL COMMENT 'quorum in the conduct of GA per by-laws',
  `voters` int(5) NOT NULL COMMENT 'no. of members entitled to vote present',
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_loans`
--

CREATE TABLE `cooperatives_loans` (
  `lid` int(6) NOT NULL,
  `cid` int(6) NOT NULL,
  `ldate` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `source` varchar(100) NOT NULL,
  `utilization` varchar(100) NOT NULL,
  `remarks` varchar(250) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_logs`
--

CREATE TABLE `cooperatives_logs` (
  `lid` int(10) NOT NULL,
  `cid` int(6) NOT NULL,
  `uid` int(4) NOT NULL,
  `ldtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='logs of who edited the cooperatives';

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_members`
--

CREATE TABLE `cooperatives_members` (
  `mid` int(10) NOT NULL,
  `cid` int(6) NOT NULL,
  `mtype` int(1) NOT NULL COMMENT '1 if regular, 0 of associate',
  `lname` char(20) NOT NULL,
  `mname` char(20) NOT NULL,
  `fname` char(20) NOT NULL,
  `addr` char(150) NOT NULL,
  `sex` char(1) NOT NULL,
  `bdate` date NOT NULL,
  `contact_num` varchar(50) NOT NULL,
  `email` varchar(40) NOT NULL,
  `children` int(2) NOT NULL COMMENT 'number of children',
  `hobbies` char(50) NOT NULL,
  `income` decimal(8,2) NOT NULL COMMENT 'approximate income',
  `sources` char(50) NOT NULL COMMENT 'other sources of income',
  `remarks` varchar(150) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_members2`
--

CREATE TABLE `cooperatives_members2` (
  `mid` int(10) NOT NULL,
  `cid` int(10) NOT NULL,
  `cyear` int(4) NOT NULL,
  `operator_rm` int(5) NOT NULL,
  `operator_rf` int(5) NOT NULL,
  `operator_am` int(5) NOT NULL,
  `operator_af` int(5) NOT NULL,
  `driver_rm` int(5) NOT NULL,
  `driver_rf` int(5) NOT NULL,
  `driver_am` int(5) NOT NULL,
  `driver_af` int(5) NOT NULL,
  `worker_rm` int(5) NOT NULL,
  `worker_rf` int(5) NOT NULL,
  `worker_am` int(5) NOT NULL,
  `worker_af` int(5) NOT NULL,
  `other_rm` int(5) NOT NULL,
  `other_rf` int(5) NOT NULL,
  `other_am` int(5) NOT NULL,
  `other_af` int(5) NOT NULL,
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_memberships`
--

CREATE TABLE `cooperatives_memberships` (
  `cmid` int(10) NOT NULL,
  `cid` int(6) NOT NULL COMMENT 'cooperative id',
  `pos_id` int(6) NOT NULL COMMENT 'tc_positions table',
  `class_id` int(2) NOT NULL COMMENT 'tc_classes table',
  `comm_id` int(2) NOT NULL COMMENT 'committee id',
  `mid` int(10) NOT NULL COMMENT 'member id',
  `ostart` date NOT NULL,
  `oend` date NOT NULL,
  `remarks` char(150) NOT NULL,
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_surplus`
--

CREATE TABLE `cooperatives_surplus` (
  `cfid` int(10) NOT NULL,
  `cid` int(6) NOT NULL,
  `cyear` year(4) NOT NULL,
  `reserved` decimal(15,2) NOT NULL COMMENT 'reserved fund',
  `educ_training` decimal(15,2) NOT NULL COMMENT 'education and training',
  `comm_dev` decimal(15,2) NOT NULL COMMENT 'community development',
  `optional` decimal(15,2) NOT NULL COMMENT 'optional fund',
  `dividends` decimal(15,2) NOT NULL COMMENT 'distribution of dividends and patronage',
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_trainings`
--

CREATE TABLE `cooperatives_trainings` (
  `ctid` int(10) NOT NULL,
  `tid` int(2) NOT NULL COMMENT 'training id',
  `tvenue` char(100) NOT NULL COMMENT 'venue',
  `fdate` date NOT NULL COMMENT 'start date',
  `tdate` date NOT NULL COMMENT 'end date',
  `eid` int(6) NOT NULL COMMENT 'trainor id',
  `cid` int(6) NOT NULL COMMENT 'cooperative id',
  `mid` int(10) NOT NULL COMMENT 'member id',
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_units`
--

CREATE TABLE `cooperatives_units` (
  `cuid` int(10) NOT NULL,
  `cid` int(6) NOT NULL,
  `cyear` year(4) NOT NULL,
  `cquarter` int(1) NOT NULL,
  `puj_num` int(6) NOT NULL,
  `mch_num` int(6) NOT NULL,
  `taxi_num` int(6) NOT NULL,
  `mb_num` int(6) NOT NULL,
  `bus_num` int(6) NOT NULL,
  `mcab_num` int(6) NOT NULL,
  `truck_num` int(6) NOT NULL,
  `tours_num` int(6) NOT NULL COMMENT 'tourist service',
  `uvx_num` int(6) NOT NULL COMMENT 'UV Express',
  `banka_num` int(6) NOT NULL,
  `class1_num` int(6) NOT NULL COMMENT 'PUVM multicab',
  `class2_num` int(6) NOT NULL COMMENT 'PUVM PUJ',
  `class3_num` int(6) NOT NULL COMMENT 'PUVM UVEx',
  `class4_num` int(6) NOT NULL COMMENT 'PUVM Cargo',
  `coop_owned` int(6) NOT NULL,
  `individual_owned` int(6) NOT NULL,
  `coop_franchised` int(6) NOT NULL,
  `individual_franchised` int(6) NOT NULL,
  `wout_franchise` int(6) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives_units2`
--

CREATE TABLE `cooperatives_units2` (
  `uid` int(10) NOT NULL,
  `cid` int(10) NOT NULL,
  `utype` int(1) NOT NULL,
  `mv_num` char(30) NOT NULL,
  `engine_num` char(30) NOT NULL,
  `chassis_num` char(30) NOT NULL,
  `plate_num` char(15) NOT NULL,
  `cpc_num` char(40) NOT NULL,
  `cpc_date` date NOT NULL,
  `cpc_exp` date NOT NULL,
  `route_start` char(30) NOT NULL,
  `route_end` char(30) NOT NULL,
  `route_via` char(30) NOT NULL,
  `vice_versa` int(1) NOT NULL,
  `owner_fname` char(30) NOT NULL,
  `owner_mname` char(30) NOT NULL,
  `owner_lname` char(30) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deductions`
--

CREATE TABLE `deductions` (
  `did` int(4) NOT NULL,
  `aid` int(2) NOT NULL COMMENT 'agency id',
  `dtype` int(1) NOT NULL COMMENT '1 premium, 2 loans',
  `dname` int(11) NOT NULL,
  `dcode1` char(15) NOT NULL,
  `dcode2` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `did` int(2) NOT NULL,
  `dname` char(50) NOT NULL,
  `dcode` char(6) NOT NULL,
  `head` int(4) NOT NULL,
  `dnum` int(4) NOT NULL COMMENT 'number of employees'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `did` int(6) NOT NULL,
  `pid` int(4) NOT NULL,
  `dnum` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `efiles`
--

CREATE TABLE `efiles` (
  `eid` int(10) NOT NULL,
  `edate` date NOT NULL,
  `tid` tinyint(2) NOT NULL,
  `did` int(2) NOT NULL,
  `etitle` varchar(100) NOT NULL,
  `edesc` varchar(100) NOT NULL,
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `efiles_files`
--

CREATE TABLE `efiles_files` (
  `ef_id` int(10) NOT NULL,
  `eid` int(10) NOT NULL,
  `efile` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `eid` int(4) NOT NULL,
  `ecode` varchar(20) NOT NULL COMMENT 'employee number given by otc',
  `etype` tinyint(1) NOT NULL COMMENT '1 Presidential Appointee, 2 Permanent, 3 Job Order, 4 Contract of Service',
  `fname` char(25) NOT NULL,
  `mname` char(25) NOT NULL,
  `lname` char(25) NOT NULL,
  `ename` char(10) NOT NULL,
  `did` int(2) NOT NULL,
  `pid` int(2) NOT NULL,
  `sgid` int(2) NOT NULL,
  `sglevel` int(2) NOT NULL,
  `time_based` int(1) NOT NULL COMMENT '1 if time-based, 0 otherwise',
  `monthly` decimal(9,2) NOT NULL COMMENT 'monthly rate',
  `daily` decimal(8,2) NOT NULL COMMENT 'daily rate',
  `phic_prem` int(1) NOT NULL COMMENT '1 if yes, 0 otherwise',
  `estart` date NOT NULL,
  `eend` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `picture` varchar(40) NOT NULL,
  `epass` varchar(32) NOT NULL,
  `esig` char(40) NOT NULL COMMENT 'electronic signature',
  `oid` int(2) NOT NULL COMMENT 'pmo office id',
  `last_update_id` int(4) NOT NULL,
  `last_update` datetime NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_banks`
--

CREATE TABLE `employees_banks` (
  `bid` int(6) NOT NULL,
  `eid` int(6) NOT NULL,
  `bank_name` char(50) NOT NULL,
  `account_num` char(20) NOT NULL COMMENT 'earned certificates of completion'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_children`
--

CREATE TABLE `employees_children` (
  `cid` int(6) NOT NULL,
  `eid` int(4) NOT NULL,
  `cname` char(50) NOT NULL,
  `bdate` date NOT NULL,
  `bplace` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_credits`
--

CREATE TABLE `employees_credits` (
  `lid` int(10) NOT NULL,
  `ldate` date NOT NULL,
  `eid` int(6) NOT NULL,
  `vl` decimal(6,3) NOT NULL,
  `sl` decimal(6,3) NOT NULL,
  `coc` int(3) NOT NULL COMMENT 'earned certificates of completion'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_eligibilities`
--

CREATE TABLE `employees_eligibilities` (
  `eeid` int(6) NOT NULL,
  `eid` int(4) NOT NULL,
  `ename` varchar(40) NOT NULL COMMENT 'eligibility',
  `rating` decimal(6,2) NOT NULL,
  `edate` date NOT NULL COMMENT 'date of examination',
  `eplace` varchar(150) NOT NULL COMMENT 'place of examination',
  `license_num` varchar(25) NOT NULL,
  `license_exp` date NOT NULL COMMENT 'date of validity'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_experiences`
--

CREATE TABLE `employees_experiences` (
  `eeid` int(6) NOT NULL,
  `eid` int(4) NOT NULL,
  `estart` date NOT NULL COMMENT 'start date of work',
  `eend` date NOT NULL COMMENT 'end date of work',
  `position` varchar(30) NOT NULL,
  `company` varchar(75) NOT NULL,
  `salary` decimal(8,2) NOT NULL,
  `sgrade` int(2) NOT NULL COMMENT 'salary grade',
  `sstep` varchar(4) NOT NULL COMMENT 'salary step "00-0"',
  `status` char(15) NOT NULL,
  `govt` int(1) NOT NULL COMMENT '1 if yes, 0 if no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_families`
--

CREATE TABLE `employees_families` (
  `fid` int(4) NOT NULL,
  `eid` int(4) NOT NULL,
  `s_lname` char(25) NOT NULL,
  `s_mname` char(25) NOT NULL,
  `s_fname` char(25) NOT NULL,
  `s_ename` char(10) NOT NULL,
  `s_occupation` varchar(30) NOT NULL,
  `s_employer` varchar(50) NOT NULL,
  `s_employer_addr` varchar(100) NOT NULL,
  `s_employer_num` varchar(30) NOT NULL,
  `f_lname` char(25) NOT NULL,
  `f_fname` char(25) NOT NULL,
  `f_mname` char(25) NOT NULL,
  `f_ename` char(10) NOT NULL,
  `m_lname` char(25) NOT NULL,
  `m_fname` char(25) NOT NULL,
  `m_mname` char(25) NOT NULL,
  `m_ename` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_hirings`
--

CREATE TABLE `employees_hirings` (
  `hid` int(6) NOT NULL,
  `eid` int(6) NOT NULL,
  `pid` int(3) NOT NULL,
  `sgid` int(2) NOT NULL,
  `status` int(1) NOT NULL,
  `hdate` date NOT NULL COMMENT 'hiring date',
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_info`
--

CREATE TABLE `employees_info` (
  `iid` int(6) NOT NULL,
  `eid` int(4) NOT NULL,
  `itype` int(1) NOT NULL COMMENT '1 skills hobbies, 2 non-acad destinctions, 3 organizations',
  `iinfo` varchar(50) NOT NULL COMMENT 'information'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_pds`
--

CREATE TABLE `employees_pds` (
  `pid` int(10) NOT NULL,
  `eid` int(4) NOT NULL,
  `sex` char(1) NOT NULL,
  `bdate` date NOT NULL,
  `bplace` char(30) NOT NULL,
  `civil_stat` int(1) NOT NULL COMMENT '1 single, 2 married, 3 separated, 4 widowed',
  `citizenship` tinyint(1) NOT NULL COMMENT '1 filipino, 2 dual',
  `citizenship_means` tinyint(1) NOT NULL COMMENT '1 birth, 2 naturalization',
  `country_dual` varchar(30) NOT NULL COMMENT 'country if dual citizen',
  `height` decimal(5,2) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `blood_type` varchar(5) NOT NULL,
  `res_addr` varchar(150) NOT NULL,
  `perm_addr` varchar(150) NOT NULL,
  `tel_no` varchar(15) NOT NULL,
  `cp_no` varchar(15) NOT NULL,
  `email` varchar(40) NOT NULL,
  `tin` varchar(15) NOT NULL,
  `gsis_id` varchar(15) NOT NULL,
  `sss_id` varchar(15) NOT NULL,
  `philhealth` varchar(15) NOT NULL,
  `pagibig` varchar(15) NOT NULL,
  `agency_num` varchar(15) NOT NULL,
  `crn` char(12) NOT NULL COMMENT 'common reference number'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_promotions`
--

CREATE TABLE `employees_promotions` (
  `epid` int(6) NOT NULL,
  `eid` int(6) NOT NULL,
  `pid` int(2) NOT NULL,
  `sgid` int(2) NOT NULL,
  `status` int(1) NOT NULL,
  `adate` date NOT NULL COMMENT 'appointment date',
  `mode` int(1) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_ratings`
--

CREATE TABLE `employees_ratings` (
  `rid` int(6) NOT NULL,
  `ryear` int(4) NOT NULL,
  `sem` int(1) NOT NULL,
  `eid` int(6) NOT NULL,
  `pid` int(2) NOT NULL,
  `rating` decimal(4,2) NOT NULL,
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_schools`
--

CREATE TABLE `employees_schools` (
  `sid` int(6) NOT NULL,
  `eid` int(4) NOT NULL COMMENT 'employee id',
  `slevel` int(1) NOT NULL COMMENT '1 elem, 2 secondary, 3 vocational, 4 college, 5 masters, 6 phd',
  `school` varchar(75) NOT NULL COMMENT 'name of school',
  `degree` varchar(50) NOT NULL COMMENT 'degree or course',
  `sstart` int(4) NOT NULL COMMENT 'start date of school',
  `send` int(4) NOT NULL COMMENT 'end date of school',
  `level_earned` int(3) NOT NULL COMMENT 'highes level or units earned if not graudated',
  `grad_yr` int(4) NOT NULL COMMENT 'year graduated',
  `honors` varchar(50) NOT NULL COMMENT 'scholarship / honors received'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_separations`
--

CREATE TABLE `employees_separations` (
  `sid` int(6) NOT NULL,
  `eid` int(6) NOT NULL,
  `pid` int(3) NOT NULL,
  `sgid` int(2) NOT NULL,
  `status` int(1) NOT NULL,
  `sdate` date NOT NULL COMMENT 'separation date',
  `mode` int(1) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_trainings`
--

CREATE TABLE `employees_trainings` (
  `tid` int(6) NOT NULL,
  `eid` int(4) NOT NULL,
  `tname` varchar(150) NOT NULL,
  `tstart` date NOT NULL,
  `tend` date NOT NULL,
  `nhours` int(3) NOT NULL,
  `ttype` varchar(25) NOT NULL COMMENT 'type of learning - managerial, supervisory, technical',
  `sponsor` varchar(30) NOT NULL COMMENT 'conducted by / sponsor'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees_voluntaries`
--

CREATE TABLE `employees_voluntaries` (
  `wid` int(6) NOT NULL,
  `eid` int(4) NOT NULL,
  `wname` varchar(50) NOT NULL COMMENT 'voluntary work name',
  `waddr` varchar(100) NOT NULL,
  `wstart` date NOT NULL COMMENT 'start of voluntary work',
  `wend` date NOT NULL COMMENT 'end of voluntary work',
  `nhours` decimal(5,2) NOT NULL COMMENT 'number of hours',
  `job_desc` varchar(75) NOT NULL COMMENT 'position / nature of work'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='employees'' voluntary works';

-- --------------------------------------------------------

--
-- Table structure for table `expenses_accounts`
--

CREATE TABLE `expenses_accounts` (
  `eid` int(6) NOT NULL,
  `year` int(4) NOT NULL,
  `month` char(12) NOT NULL,
  `aname` char(100) NOT NULL,
  `acode` int(10) NOT NULL,
  `total` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_types`
--

CREATE TABLE `file_types` (
  `tid` int(2) NOT NULL,
  `tname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `franchises`
--

CREATE TABLE `franchises` (
  `fid` int(10) NOT NULL,
  `cid` int(6) NOT NULL,
  `utype2` int(1) NOT NULL COMMENT '1 if existing, 0 if proposed',
  `utype` int(1) NOT NULL COMMENT '1 puj, 2 auv, 3 taxi, 4 multicab, 5 mini bus, 6 bus, 7 mch, 8 truck, 9 banca, 10 others',
  `units_num` int(5) NOT NULL,
  `route_start` varchar(150) NOT NULL,
  `route_end` varchar(150) NOT NULL,
  `vice_versa` tinyint(1) NOT NULL COMMENT '1 if yes, 0 otherwise',
  `ltfrb_case` varchar(75) NOT NULL,
  `exp_date` date NOT NULL,
  `own_type` int(1) NOT NULL COMMENT '1 coop-owned, 2 individual-owned',
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `franchises_ltfrb`
--

CREATE TABLE `franchises_ltfrb` (
  `fid` int(10) NOT NULL,
  `sid` int(3) NOT NULL,
  `case_num` char(15) NOT NULL,
  `unit_type` char(25) NOT NULL COMMENT 'type of unit',
  `owner` char(120) NOT NULL,
  `owner_type` char(25) NOT NULL COMMENT 'type of owner',
  `units_num` int(6) NOT NULL,
  `dg_date` date NOT NULL,
  `de_date` date NOT NULL,
  `route_code` char(15) NOT NULL,
  `route` char(150) NOT NULL,
  `remarks` char(100) NOT NULL,
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL,
  `foreign_id` int(10) NOT NULL COMMENT 'cooperative id or member id'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `franchises_ltfrb2`
--

CREATE TABLE `franchises_ltfrb2` (
  `fid` int(10) NOT NULL,
  `sid` int(3) NOT NULL,
  `case_num` char(15) NOT NULL,
  `unit_type` char(25) NOT NULL COMMENT 'type of unit',
  `owner` char(120) NOT NULL,
  `owner_type` char(25) NOT NULL COMMENT 'type of owner',
  `units_num` int(6) NOT NULL,
  `dg_date` date NOT NULL,
  `de_date` date NOT NULL,
  `route_code` char(15) NOT NULL,
  `route` char(150) NOT NULL,
  `remarks` char(100) NOT NULL,
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL,
  `foreign_id` int(10) NOT NULL COMMENT 'cooperative id or member id'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internals_dms`
--

CREATE TABLE `internals_dms` (
  `did` int(10) NOT NULL,
  `dnum` char(15) NOT NULL,
  `eid` int(6) NOT NULL,
  `fr_time` time NOT NULL,
  `to_time` time NOT NULL,
  `ddate` date NOT NULL,
  `destination` char(50) NOT NULL,
  `purpose` char(100) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internals_leave_applications`
--

CREATE TABLE `internals_leave_applications` (
  `aid` int(10) NOT NULL,
  `anum` char(15) NOT NULL,
  `eid` int(6) NOT NULL,
  `lid` int(2) NOT NULL COMMENT 'leave type',
  `fr_date` date NOT NULL,
  `to_date` date NOT NULL,
  `lnum` int(3) NOT NULL COMMENT 'number of days',
  `stats` int(1) NOT NULL COMMENT '-1 disapproved, 0 pending, 1 approved',
  `remarks` char(50) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL,
  `approve_id` int(6) NOT NULL,
  `approve_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internals_memo_circulars`
--

CREATE TABLE `internals_memo_circulars` (
  `cid` int(10) NOT NULL,
  `cdate` date NOT NULL,
  `cnum` char(15) NOT NULL,
  `addressee` char(50) NOT NULL,
  `subject` char(75) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internals_memo_orders`
--

CREATE TABLE `internals_memo_orders` (
  `oid` int(10) NOT NULL,
  `odate` date NOT NULL,
  `fr_date` date NOT NULL,
  `to_date` date NOT NULL,
  `onum` char(15) NOT NULL,
  `enames` char(100) NOT NULL,
  `subject` char(150) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internals_memo_orders_employees`
--

CREATE TABLE `internals_memo_orders_employees` (
  `me_id` int(10) NOT NULL,
  `oid` int(10) NOT NULL,
  `eid` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internals_office_orders`
--

CREATE TABLE `internals_office_orders` (
  `oid` int(10) NOT NULL,
  `odate` date NOT NULL,
  `onum` char(15) NOT NULL,
  `enames` char(100) NOT NULL,
  `subject` char(75) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internals_special_orders`
--

CREATE TABLE `internals_special_orders` (
  `sid` int(10) NOT NULL,
  `sdate` date NOT NULL,
  `snum` char(15) NOT NULL,
  `enames` char(100) NOT NULL,
  `subject` char(75) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internals_travel_orders`
--

CREATE TABLE `internals_travel_orders` (
  `tid` int(10) NOT NULL,
  `tnum` char(15) NOT NULL,
  `eid` int(6) NOT NULL,
  `fr_date` date NOT NULL,
  `to_date` date NOT NULL,
  `destination` char(50) NOT NULL,
  `purpose` char(100) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internals_travel_orders_employees`
--

CREATE TABLE `internals_travel_orders_employees` (
  `te_id` int(10) NOT NULL,
  `tid` int(10) NOT NULL,
  `eid` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `iid` int(6) NOT NULL,
  `cid` int(2) NOT NULL,
  `idesc` varchar(75) NOT NULL,
  `property_num` varchar(15) NOT NULL,
  `property_ack_num` varchar(15) NOT NULL COMMENT 'property acknowledgement number',
  `ics` varchar(15) NOT NULL COMMENT 'inventory custodian slip number',
  `sid` int(3) NOT NULL,
  `brand_name` char(10) NOT NULL,
  `model_name` varchar(25) NOT NULL,
  `serial` varchar(25) NOT NULL,
  `acquired_date` date NOT NULL,
  `acquired_value` decimal(10,2) NOT NULL,
  `depreciated_date` date NOT NULL,
  `depreciated_value` decimal(10,2) NOT NULL,
  `current_value` decimal(10,2) NOT NULL,
  `eid` int(3) NOT NULL,
  `remarks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_conversions`
--

CREATE TABLE `leave_conversions` (
  `cid` int(3) NOT NULL,
  `minute` int(2) NOT NULL,
  `conversion` decimal(5,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `lid` int(2) NOT NULL,
  `lname` char(20) NOT NULL,
  `increment` decimal(4,2) NOT NULL,
  `yr_limit` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201807`
--

CREATE TABLE `logs_201807` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201808`
--

CREATE TABLE `logs_201808` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201809`
--

CREATE TABLE `logs_201809` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201810`
--

CREATE TABLE `logs_201810` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201811`
--

CREATE TABLE `logs_201811` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201812`
--

CREATE TABLE `logs_201812` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201901`
--

CREATE TABLE `logs_201901` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201902`
--

CREATE TABLE `logs_201902` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201903`
--

CREATE TABLE `logs_201903` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201904`
--

CREATE TABLE `logs_201904` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201905`
--

CREATE TABLE `logs_201905` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201906`
--

CREATE TABLE `logs_201906` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201907`
--

CREATE TABLE `logs_201907` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201908`
--

CREATE TABLE `logs_201908` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201909`
--

CREATE TABLE `logs_201909` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201910`
--

CREATE TABLE `logs_201910` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201911`
--

CREATE TABLE `logs_201911` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_201912`
--

CREATE TABLE `logs_201912` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_202001`
--

CREATE TABLE `logs_202001` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_202002`
--

CREATE TABLE `logs_202002` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_202003`
--

CREATE TABLE `logs_202003` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_202004`
--

CREATE TABLE `logs_202004` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs_202508`
--

CREATE TABLE `logs_202508` (
  `log_id` bigint(20) NOT NULL,
  `log_dtime` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print, 0 log in/out',
  `user_id` int(4) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `pkid` int(6) NOT NULL COMMENT 'pk id of accessed data'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `page_id` int(4) NOT NULL,
  `nav_id` tinyint(2) NOT NULL,
  `parent_id` int(4) NOT NULL,
  `grp_num` int(2) NOT NULL,
  `page_name` varchar(50) NOT NULL,
  `page_desc` varchar(150) NOT NULL,
  `action` tinyint(1) NOT NULL COMMENT '1 add, 2 edit, 3 delete, 4 verify/confirm, 5 view, 6 print'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `pid` int(10) NOT NULL,
  `sid` int(10) NOT NULL COMMENT 'salary period id',
  `gid` int(2) NOT NULL COMMENT 'salary group id',
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL,
  `user2_id` int(4) NOT NULL,
  `user2_dtime` datetime NOT NULL,
  `total_gross` decimal(15,2) NOT NULL,
  `total_net` decimal(12,2) NOT NULL,
  `gsis_ee` decimal(9,2) NOT NULL,
  `gsis_er` decimal(9,2) NOT NULL,
  `hdmf_ee` decimal(9,2) NOT NULL,
  `hdmf_er` decimal(9,2) NOT NULL,
  `phic_ee` decimal(9,2) NOT NULL,
  `phic_er` decimal(9,2) NOT NULL,
  `sss_ee` decimal(9,2) NOT NULL,
  `sss_er` decimal(9,2) NOT NULL,
  `gsis_loans` decimal(10,2) NOT NULL,
  `hdmf_loans` decimal(10,2) NOT NULL,
  `sss_loans` decimal(10,2) NOT NULL,
  `per_tax` decimal(12,2) NOT NULL,
  `ewt_tax` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls_adjustments`
--

CREATE TABLE `payrolls_adjustments` (
  `aid` int(6) NOT NULL,
  `sp_id` int(6) NOT NULL,
  `emp_id` int(6) NOT NULL,
  `atype` int(1) NOT NULL COMMENT '1 13th_month, 2 OT pay, 3 Leave pay, 4 Holiday pay, 5 Other pay, 6 tardiness, 7 penalties, 8 other deductions',
  `adjustment` decimal(7,2) NOT NULL,
  `remarks` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Payroll adjustments';

-- --------------------------------------------------------

--
-- Table structure for table `payrolls_details`
--

CREATE TABLE `payrolls_details` (
  `pd_id` int(15) NOT NULL,
  `pr_id` int(10) NOT NULL,
  `emp_id` int(4) NOT NULL,
  `wdays` decimal(4,2) NOT NULL,
  `adays` decimal(4,2) NOT NULL,
  `daily` decimal(6,2) NOT NULL,
  `salary` decimal(8,2) NOT NULL,
  `sub_total` decimal(8,2) NOT NULL,
  `deminimis` decimal(8,2) NOT NULL,
  `benefits` decimal(8,2) NOT NULL,
  `ecola` decimal(8,2) NOT NULL,
  `other_benefits` decimal(8,2) NOT NULL,
  `13th_pay` decimal(8,2) NOT NULL,
  `ot_pay` decimal(8,2) NOT NULL,
  `leave_pay` decimal(8,2) NOT NULL,
  `holiday_pay` decimal(8,2) NOT NULL,
  `other_pay` decimal(8,2) NOT NULL,
  `tardiness` decimal(8,2) NOT NULL,
  `penalties` decimal(8,2) NOT NULL,
  `other_deductions` decimal(8,2) NOT NULL,
  `short_over` decimal(7,2) NOT NULL,
  `gross` decimal(8,2) NOT NULL,
  `mgross` decimal(10,2) NOT NULL,
  `taxable` decimal(10,2) NOT NULL,
  `e_sss` decimal(6,2) NOT NULL,
  `er_sss` decimal(6,2) NOT NULL,
  `e_hdmf` decimal(6,2) NOT NULL,
  `er_hdmf` decimal(6,2) NOT NULL,
  `e_phealth` decimal(6,2) NOT NULL,
  `er_phealth` decimal(6,2) NOT NULL,
  `loan_sss` decimal(7,2) NOT NULL,
  `loan_hdmf` decimal(7,2) NOT NULL,
  `loan_cashbond` decimal(8,2) NOT NULL,
  `cash_advance` decimal(8,2) NOT NULL,
  `cash_bond` decimal(8,2) NOT NULL,
  `tdeductions` decimal(8,2) NOT NULL,
  `tax` decimal(8,2) NOT NULL,
  `net_salary` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls_groups`
--

CREATE TABLE `payrolls_groups` (
  `gid` int(2) NOT NULL,
  `gname` char(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls_jocos`
--

CREATE TABLE `payrolls_jocos` (
  `jid` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  `eid` int(6) NOT NULL,
  `daily` decimal(7,2) NOT NULL,
  `salary` decimal(8,2) NOT NULL,
  `wdays` int(2) NOT NULL,
  `tut_hr` int(42) NOT NULL COMMENT 'tardiness / UT in hour',
  `tut_min` int(4) NOT NULL COMMENT 'tardiness / UT in minutes',
  `gross` decimal(8,2) NOT NULL,
  `tut_value` decimal(8,2) NOT NULL COMMENT 'tardiness / UT in peso',
  `net` decimal(8,2) NOT NULL,
  `phic` decimal(6,2) NOT NULL,
  `ewt` decimal(6,2) NOT NULL,
  `vat` decimal(6,2) NOT NULL,
  `deductions` decimal(6,2) NOT NULL,
  `payable` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls_regular`
--

CREATE TABLE `payrolls_regular` (
  `rid` int(10) NOT NULL,
  `pid` int(10) NOT NULL COMMENT 'payroll id',
  `eid` int(6) NOT NULL COMMENT 'employee id',
  `basic` decimal(9,2) NOT NULL,
  `gsis_rlip_er` decimal(8,2) NOT NULL COMMENT 'rlip er share',
  `gsis_rlip_ee` decimal(8,2) NOT NULL COMMENT 'gsis rlip',
  `gsis_uoli` decimal(8,2) NOT NULL COMMENT 'gsis uoli',
  `gsis_consoloan` decimal(8,2) NOT NULL COMMENT 'gsis consoloan',
  `gsis_cash_adv` decimal(8,2) NOT NULL COMMENT 'gsis cash adv',
  `gsis_eal` decimal(8,2) NOT NULL COMMENT 'gsis eal',
  `gsis_el` decimal(8,2) NOT NULL COMMENT 'gsis el',
  `gsis_pl` decimal(8,2) NOT NULL COMMENT 'gsis pl',
  `gsis_opt_pl` decimal(8,2) NOT NULL COMMENT 'gsis opt pl',
  `hdmf_ee` decimal(8,2) NOT NULL COMMENT 'hdmf ee share',
  `hdmf_er` decimal(8,2) NOT NULL COMMENT 'hdmf er share',
  `hdmf_hl` decimal(8,2) NOT NULL COMMENT 'hdmf housing loan',
  `hdmf_mpl` decimal(8,2) NOT NULL COMMENT 'hdmf mpl',
  `phic_ee` decimal(8,2) NOT NULL COMMENT 'phic ee share',
  `phic_er` decimal(8,2) NOT NULL COMMENT 'phic er share',
  `emdeco` decimal(8,2) NOT NULL COMMENT 'otc-emdeco',
  `bir_tax` decimal(8,2) NOT NULL COMMENT 'bir tax withheld',
  `dad` decimal(8,2) NOT NULL COMMENT 'deductions / absences / disallowances',
  `total` decimal(9,2) NOT NULL COMMENT 'total deductions',
  `net` decimal(9,2) NOT NULL,
  `net1` decimal(8,2) NOT NULL,
  `net2` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls_regular2`
--

CREATE TABLE `payrolls_regular2` (
  `rid` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  `eid` int(6) NOT NULL,
  `basic` decimal(9,2) NOT NULL,
  `absences` decimal(8,2) NOT NULL COMMENT 'absences / disallowances',
  `deductions` decimal(8,2) NOT NULL COMMENT 'total deductions',
  `net` decimal(9,2) NOT NULL COMMENT 'monthly net pay',
  `net1` decimal(8,2) NOT NULL COMMENT '1st payroll',
  `net2` decimal(8,2) NOT NULL COMMENT '2nd payroll'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pmo_offices`
--

CREATE TABLE `pmo_offices` (
  `oid` int(2) NOT NULL,
  `oname` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pmo_reports`
--

CREATE TABLE `pmo_reports` (
  `rid` int(10) NOT NULL,
  `eid` int(6) NOT NULL,
  `rstart` date NOT NULL,
  `rend` date NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pmo_reports_details`
--

CREATE TABLE `pmo_reports_details` (
  `prid` int(10) NOT NULL,
  `rid` int(10) NOT NULL,
  `cid` int(1) NOT NULL COMMENT 'category id',
  `rdetails` text NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pmo_reports_efiles`
--

CREATE TABLE `pmo_reports_efiles` (
  `rfid` int(10) NOT NULL,
  `rid` int(10) NOT NULL,
  `rfile` char(100) NOT NULL,
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `pid` int(2) NOT NULL,
  `pname` char(50) NOT NULL,
  `sgid` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurements`
--

CREATE TABLE `procurements` (
  `pid` int(10) NOT NULL,
  `ref_num` char(50) NOT NULL,
  `did` int(2) NOT NULL COMMENT 'division',
  `eid` int(6) NOT NULL COMMENT 'employee',
  `proc_name` char(150) NOT NULL,
  `budget` decimal(12,2) NOT NULL,
  `ptid` int(3) NOT NULL COMMENT 'procurement type',
  `pmid` int(3) NOT NULL COMMENT 'procurement mode',
  `publish_date` date NOT NULL,
  `closing_date` date NOT NULL,
  `status` int(2) NOT NULL,
  `remarks` char(150) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement_modes`
--

CREATE TABLE `procurement_modes` (
  `pmid` int(3) NOT NULL,
  `pm_name` char(100) NOT NULL,
  `pm_desc` char(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement_requirements`
--

CREATE TABLE `procurement_requirements` (
  `prid` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  `pr_num` int(2) NOT NULL,
  `rid` int(6) NOT NULL,
  `eid` int(6) NOT NULL,
  `finish_date` date NOT NULL,
  `remarks` char(75) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement_types`
--

CREATE TABLE `procurement_types` (
  `ptid` int(3) NOT NULL,
  `pt_name` char(50) NOT NULL,
  `pt_desc` char(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `pid` int(5) NOT NULL,
  `rid` int(2) NOT NULL,
  `pname` char(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='provinces';

-- --------------------------------------------------------

--
-- Table structure for table `psipop`
--

CREATE TABLE `psipop` (
  `psid` int(3) NOT NULL COMMENT 'pk',
  `inum` char(50) NOT NULL COMMENT 'item num',
  `pid` int(3) NOT NULL COMMENT 'position',
  `sid` int(2) NOT NULL COMMENT 'salary grade',
  `did` int(2) NOT NULL COMMENT 'department',
  `eid` int(4) NOT NULL COMMENT 'employee'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `qid` int(4) NOT NULL,
  `qdate` date NOT NULL,
  `quote` varchar(200) NOT NULL,
  `author` char(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `rid` int(2) NOT NULL,
  `rname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `rid` int(10) NOT NULL,
  `rtid` int(3) NOT NULL,
  `eid` int(6) NOT NULL COMMENT 'employee id',
  `did` int(2) NOT NULL COMMENT 'division id',
  `due_date` date NOT NULL,
  `submit_date` date NOT NULL COMMENT 'date first submitted',
  `accepted_by` int(6) NOT NULL,
  `accepted_date` date NOT NULL,
  `status` int(1) NOT NULL COMMENT '1 accepted, 0 on process, -1 returned, 2 not submitted',
  `remarks` char(250) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports_efiles`
--

CREATE TABLE `reports_efiles` (
  `rfid` int(10) NOT NULL,
  `rid` int(10) NOT NULL,
  `rfile` char(100) NOT NULL,
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports_types`
--

CREATE TABLE `reports_types` (
  `rtid` int(3) NOT NULL,
  `rt_name` char(100) NOT NULL,
  `frequency` int(1) NOT NULL COMMENT '1 monthly, 2 quarterly, 3 semi-annually, 4 annually',
  `due_date` int(2) NOT NULL COMMENT 'day of the month indicating the due date'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requirements`
--

CREATE TABLE `requirements` (
  `rid` int(6) NOT NULL,
  `ptid` int(3) NOT NULL COMMENT 'procurement type',
  `rnum` int(2) NOT NULL COMMENT 'order of requirement',
  `rname` char(125) NOT NULL COMMENT 'name of requiremnt',
  `reid` int(6) NOT NULL COMMENT 'default responsible employee '
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requirements_efiles`
--

CREATE TABLE `requirements_efiles` (
  `rfid` int(10) NOT NULL,
  `prid` int(10) NOT NULL,
  `efile` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `rid` int(10) NOT NULL,
  `cid` int(6) NOT NULL,
  `rstart` char(40) NOT NULL COMMENT 'point of origin',
  `rend` char(40) NOT NULL COMMENT 'destination'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `routes_details`
--

CREATE TABLE `routes_details` (
  `rdid` int(10) NOT NULL,
  `rid` int(10) NOT NULL,
  `rdnum` int(2) NOT NULL COMMENT 'sort',
  `rdplace` char(40) NOT NULL COMMENT 'place - via'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `routings`
--

CREATE TABLE `routings` (
  `rid` int(10) NOT NULL,
  `cid` int(10) NOT NULL,
  `fdate` date NOT NULL COMMENT 'filled-up date',
  `from_user_id` int(4) NOT NULL COMMENT 'from name/position',
  `to_user_id` int(4) NOT NULL COMMENT 'to name/position',
  `remarks` varchar(500) NOT NULL,
  `recipient` varchar(30) NOT NULL,
  `rdate` date NOT NULL COMMENT 'received date',
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL,
  `receiver_id` int(6) NOT NULL,
  `receiver_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_grades`
--

CREATE TABLE `salary_grades` (
  `sgid` int(2) NOT NULL,
  `sg_num` int(2) NOT NULL,
  `basic2018` decimal(9,2) NOT NULL,
  `basic2019` decimal(9,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_periods`
--

CREATE TABLE `salary_periods` (
  `sid` int(6) NOT NULL,
  `gid` int(2) NOT NULL,
  `sp_start` date NOT NULL,
  `sp_end` date NOT NULL,
  `sp_date` date NOT NULL,
  `wdays` int(2) NOT NULL,
  `phic_prem` int(1) NOT NULL COMMENT '1 if yes, 0 otherwise'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_tranches`
--

CREATE TABLE `salary_tranches` (
  `sid` int(4) NOT NULL,
  `tid` int(3) NOT NULL,
  `snum` int(2) NOT NULL COMMENT 'salary grade number',
  `step1` int(7) NOT NULL,
  `step2` int(7) NOT NULL,
  `step3` int(7) NOT NULL,
  `step4` int(7) NOT NULL,
  `step5` int(7) NOT NULL,
  `step6` int(7) NOT NULL,
  `step7` int(7) NOT NULL,
  `step8` int(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `user_id` int(6) UNSIGNED ZEROFILL NOT NULL COMMENT 'primary key',
  `session_id` varchar(255) NOT NULL DEFAULT '',
  `session_key` varchar(32) NOT NULL,
  `session_token` varchar(32) NOT NULL,
  `activity` int(12) NOT NULL COMMENT 'time()',
  `session_ip` varchar(255) NOT NULL DEFAULT '',
  `refurl` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ssls`
--

CREATE TABLE `ssls` (
  `sid` int(2) NOT NULL,
  `eo_num` char(75) NOT NULL COMMENT 'executive order number',
  `title` char(250) NOT NULL,
  `edate` date NOT NULL COMMENT 'date of effectivity',
  `active` int(1) NOT NULL COMMENT '1 if current, 0 if past law'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Salary Standardization Laws';

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `sid` int(4) UNSIGNED NOT NULL,
  `tin` varchar(15) NOT NULL,
  `company` varchar(150) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `person` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `address` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tc_areas`
--

CREATE TABLE `tc_areas` (
  `aid` int(2) NOT NULL,
  `aname` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tc_classes`
--

CREATE TABLE `tc_classes` (
  `tcid` int(2) NOT NULL,
  `class_name` char(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tc_committees`
--

CREATE TABLE `tc_committees` (
  `comm_id` int(2) NOT NULL,
  `comm_name` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tc_officers`
--

CREATE TABLE `tc_officers` (
  `oid` int(2) NOT NULL,
  `oname` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tc_positions`
--

CREATE TABLE `tc_positions` (
  `pid` int(2) NOT NULL,
  `pname` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tc_sectors`
--

CREATE TABLE `tc_sectors` (
  `sid` int(3) NOT NULL,
  `aid` int(2) NOT NULL,
  `sname` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tc_trainings`
--

CREATE TABLE `tc_trainings` (
  `tid` int(2) NOT NULL,
  `tcode` char(10) NOT NULL,
  `tname` char(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tc_transactions`
--

CREATE TABLE `tc_transactions` (
  `tid` int(2) NOT NULL,
  `tname` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `towns`
--

CREATE TABLE `towns` (
  `tid` int(6) NOT NULL,
  `did` int(6) NOT NULL,
  `zipcode` int(5) NOT NULL,
  `pid` int(5) NOT NULL,
  `tname` char(30) NOT NULL,
  `vnum` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='cities or towns';

-- --------------------------------------------------------

--
-- Table structure for table `towns_backup`
--

CREATE TABLE `towns_backup` (
  `tid` int(6) NOT NULL,
  `did` int(6) NOT NULL,
  `zipcode` int(5) NOT NULL,
  `pid` int(5) NOT NULL,
  `tname` char(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='cities or towns';

-- --------------------------------------------------------

--
-- Table structure for table `trainings`
--

CREATE TABLE `trainings` (
  `tid` int(10) NOT NULL,
  `fdate` date NOT NULL COMMENT 'from date',
  `tdate` date NOT NULL COMMENT 'to date',
  `tvenue` char(100) NOT NULL,
  `trainer1` int(6) NOT NULL COMMENT 'employee id',
  `trainer2` int(6) NOT NULL COMMENT 'employee id',
  `trainer3` int(6) NOT NULL COMMENT 'employee id',
  `training_id` int(2) NOT NULL COMMENT 'training id',
  `remarks` char(75) NOT NULL,
  `user_id` int(6) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tranches`
--

CREATE TABLE `tranches` (
  `tid` int(3) NOT NULL,
  `sid` int(2) NOT NULL,
  `tnum` int(2) NOT NULL COMMENT 'tranche number',
  `active` int(11) NOT NULL COMMENT '1 if current, 0 if previous'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='SSL Tranches';

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `tid` int(10) NOT NULL,
  `cid` int(10) NOT NULL COMMENT 'communication id',
  `ref_num` char(15) NOT NULL COMMENT 'reference number',
  `fr_dtime` datetime NOT NULL COMMENT 'date and time of transaction',
  `to_dtime` datetime NOT NULL,
  `ttid` int(2) NOT NULL COMMENT 'type of transaction',
  `tname` char(40) NOT NULL COMMENT 'person transacting',
  `coop_id` int(6) NOT NULL COMMENT 'cooperative id',
  `status` int(1) NOT NULL COMMENT '0 received by records, 1 pre-evaluation, 2 forwarded to OC,',
  `remarks` char(50) NOT NULL,
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions_efiles`
--

CREATE TABLE `transactions_efiles` (
  `tfid` int(10) NOT NULL,
  `tid` int(10) NOT NULL,
  `tfile` varchar(50) NOT NULL,
  `user_id` int(4) NOT NULL,
  `user_dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions_requirements`
--

CREATE TABLE `transactions_requirements` (
  `rid` int(10) NOT NULL,
  `tid` int(10) NOT NULL,
  `rname` char(50) NOT NULL COMMENT 'name of requirement',
  `fr_dtime` datetime NOT NULL COMMENT 'date/time requirement processed',
  `to_dtime` datetime NOT NULL,
  `user_id` int(6) NOT NULL,
  `status` int(1) NOT NULL COMMENT '0 pending, 1 passed ',
  `remarks` char(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(3) UNSIGNED NOT NULL,
  `fname` varchar(25) NOT NULL,
  `lname` varchar(25) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `ulevel` tinyint(1) NOT NULL COMMENT '1 super user; 2 admin; 3 dept chief; 4 dept sec; 5 level 1 user',
  `eid` int(4) NOT NULL,
  `last_login` datetime NOT NULL,
  `last_ip` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `users_accesses`
--

CREATE TABLE `users_accesses` (
  `ua_id` int(10) NOT NULL,
  `aid` int(4) NOT NULL,
  `user_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `year_months`
--

CREATE TABLE `year_months` (
  `id` int(4) NOT NULL,
  `yyyymm` int(6) NOT NULL,
  `month_year` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accesses`
--
ALTER TABLE `accesses`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `accesses_pages`
--
ALTER TABLE `accesses_pages`
  ADD PRIMARY KEY (`ap_id`);

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `activities_people`
--
ALTER TABLE `activities_people`
  ADD PRIMARY KEY (`apid`);

--
-- Indexes for table `agencies`
--
ALTER TABLE `agencies`
  ADD PRIMARY KEY (`agency_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `brgys`
--
ALTER TABLE `brgys`
  ADD PRIMARY KEY (`bid`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `cgs_efiles`
--
ALTER TABLE `cgs_efiles`
  ADD PRIMARY KEY (`cfid`);

--
-- Indexes for table `cocs`
--
ALTER TABLE `cocs`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `communications`
--
ALTER TABLE `communications`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `communications_efiles`
--
ALTER TABLE `communications_efiles`
  ADD PRIMARY KEY (`cfid`);

--
-- Indexes for table `cooperatives`
--
ALTER TABLE `cooperatives`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `cooperatives_amendments`
--
ALTER TABLE `cooperatives_amendments`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `cooperatives_backup`
--
ALTER TABLE `cooperatives_backup`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `cooperatives_businesses`
--
ALTER TABLE `cooperatives_businesses`
  ADD PRIMARY KEY (`bid`);

--
-- Indexes for table `cooperatives_capitalizations`
--
ALTER TABLE `cooperatives_capitalizations`
  ADD PRIMARY KEY (`cfid`);

--
-- Indexes for table `cooperatives_cda`
--
ALTER TABLE `cooperatives_cda`
  ADD PRIMARY KEY (`ccid`);

--
-- Indexes for table `cooperatives_cgs`
--
ALTER TABLE `cooperatives_cgs`
  ADD PRIMARY KEY (`ccid`);

--
-- Indexes for table `cooperatives_classes`
--
ALTER TABLE `cooperatives_classes`
  ADD PRIMARY KEY (`ccid`);

--
-- Indexes for table `cooperatives_donations`
--
ALTER TABLE `cooperatives_donations`
  ADD PRIMARY KEY (`did`);

--
-- Indexes for table `cooperatives_financials`
--
ALTER TABLE `cooperatives_financials`
  ADD PRIMARY KEY (`cfid`);

--
-- Indexes for table `cooperatives_governances`
--
ALTER TABLE `cooperatives_governances`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `cooperatives_loans`
--
ALTER TABLE `cooperatives_loans`
  ADD PRIMARY KEY (`lid`);

--
-- Indexes for table `cooperatives_logs`
--
ALTER TABLE `cooperatives_logs`
  ADD PRIMARY KEY (`lid`);

--
-- Indexes for table `cooperatives_members`
--
ALTER TABLE `cooperatives_members`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `cooperatives_members2`
--
ALTER TABLE `cooperatives_members2`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `cooperatives_memberships`
--
ALTER TABLE `cooperatives_memberships`
  ADD PRIMARY KEY (`cmid`);

--
-- Indexes for table `cooperatives_surplus`
--
ALTER TABLE `cooperatives_surplus`
  ADD PRIMARY KEY (`cfid`);

--
-- Indexes for table `cooperatives_trainings`
--
ALTER TABLE `cooperatives_trainings`
  ADD PRIMARY KEY (`ctid`);

--
-- Indexes for table `cooperatives_units`
--
ALTER TABLE `cooperatives_units`
  ADD PRIMARY KEY (`cuid`);

--
-- Indexes for table `cooperatives_units2`
--
ALTER TABLE `cooperatives_units2`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `deductions`
--
ALTER TABLE `deductions`
  ADD PRIMARY KEY (`did`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`did`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`did`);

--
-- Indexes for table `efiles`
--
ALTER TABLE `efiles`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `efiles_files`
--
ALTER TABLE `efiles_files`
  ADD PRIMARY KEY (`ef_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `employees_banks`
--
ALTER TABLE `employees_banks`
  ADD PRIMARY KEY (`bid`);

--
-- Indexes for table `employees_children`
--
ALTER TABLE `employees_children`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `employees_credits`
--
ALTER TABLE `employees_credits`
  ADD PRIMARY KEY (`lid`);

--
-- Indexes for table `employees_eligibilities`
--
ALTER TABLE `employees_eligibilities`
  ADD PRIMARY KEY (`eeid`);

--
-- Indexes for table `employees_experiences`
--
ALTER TABLE `employees_experiences`
  ADD PRIMARY KEY (`eeid`);

--
-- Indexes for table `employees_families`
--
ALTER TABLE `employees_families`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `employees_hirings`
--
ALTER TABLE `employees_hirings`
  ADD PRIMARY KEY (`hid`);

--
-- Indexes for table `employees_info`
--
ALTER TABLE `employees_info`
  ADD PRIMARY KEY (`iid`);

--
-- Indexes for table `employees_pds`
--
ALTER TABLE `employees_pds`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `employees_promotions`
--
ALTER TABLE `employees_promotions`
  ADD PRIMARY KEY (`epid`);

--
-- Indexes for table `employees_ratings`
--
ALTER TABLE `employees_ratings`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `employees_schools`
--
ALTER TABLE `employees_schools`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `employees_separations`
--
ALTER TABLE `employees_separations`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `employees_trainings`
--
ALTER TABLE `employees_trainings`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `employees_voluntaries`
--
ALTER TABLE `employees_voluntaries`
  ADD PRIMARY KEY (`wid`);

--
-- Indexes for table `expenses_accounts`
--
ALTER TABLE `expenses_accounts`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `file_types`
--
ALTER TABLE `file_types`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `franchises`
--
ALTER TABLE `franchises`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `franchises_ltfrb`
--
ALTER TABLE `franchises_ltfrb`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `franchises_ltfrb2`
--
ALTER TABLE `franchises_ltfrb2`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `internals_dms`
--
ALTER TABLE `internals_dms`
  ADD PRIMARY KEY (`did`);

--
-- Indexes for table `internals_leave_applications`
--
ALTER TABLE `internals_leave_applications`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `internals_memo_circulars`
--
ALTER TABLE `internals_memo_circulars`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `internals_memo_orders`
--
ALTER TABLE `internals_memo_orders`
  ADD PRIMARY KEY (`oid`);

--
-- Indexes for table `internals_memo_orders_employees`
--
ALTER TABLE `internals_memo_orders_employees`
  ADD PRIMARY KEY (`me_id`);

--
-- Indexes for table `internals_office_orders`
--
ALTER TABLE `internals_office_orders`
  ADD PRIMARY KEY (`oid`);

--
-- Indexes for table `internals_special_orders`
--
ALTER TABLE `internals_special_orders`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `internals_travel_orders`
--
ALTER TABLE `internals_travel_orders`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `internals_travel_orders_employees`
--
ALTER TABLE `internals_travel_orders_employees`
  ADD PRIMARY KEY (`te_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`iid`);

--
-- Indexes for table `leave_conversions`
--
ALTER TABLE `leave_conversions`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`lid`);

--
-- Indexes for table `logs_201807`
--
ALTER TABLE `logs_201807`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201808`
--
ALTER TABLE `logs_201808`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201809`
--
ALTER TABLE `logs_201809`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201810`
--
ALTER TABLE `logs_201810`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201811`
--
ALTER TABLE `logs_201811`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201812`
--
ALTER TABLE `logs_201812`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201901`
--
ALTER TABLE `logs_201901`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201902`
--
ALTER TABLE `logs_201902`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201903`
--
ALTER TABLE `logs_201903`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201904`
--
ALTER TABLE `logs_201904`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201905`
--
ALTER TABLE `logs_201905`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201906`
--
ALTER TABLE `logs_201906`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201907`
--
ALTER TABLE `logs_201907`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201908`
--
ALTER TABLE `logs_201908`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201909`
--
ALTER TABLE `logs_201909`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201910`
--
ALTER TABLE `logs_201910`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201911`
--
ALTER TABLE `logs_201911`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_201912`
--
ALTER TABLE `logs_201912`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_202001`
--
ALTER TABLE `logs_202001`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_202002`
--
ALTER TABLE `logs_202002`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_202003`
--
ALTER TABLE `logs_202003`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_202004`
--
ALTER TABLE `logs_202004`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `logs_202508`
--
ALTER TABLE `logs_202508`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `payrolls_adjustments`
--
ALTER TABLE `payrolls_adjustments`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `payrolls_details`
--
ALTER TABLE `payrolls_details`
  ADD PRIMARY KEY (`pd_id`);

--
-- Indexes for table `payrolls_groups`
--
ALTER TABLE `payrolls_groups`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `payrolls_jocos`
--
ALTER TABLE `payrolls_jocos`
  ADD PRIMARY KEY (`jid`);

--
-- Indexes for table `payrolls_regular`
--
ALTER TABLE `payrolls_regular`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `payrolls_regular2`
--
ALTER TABLE `payrolls_regular2`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `pmo_offices`
--
ALTER TABLE `pmo_offices`
  ADD PRIMARY KEY (`oid`);

--
-- Indexes for table `pmo_reports`
--
ALTER TABLE `pmo_reports`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `pmo_reports_details`
--
ALTER TABLE `pmo_reports_details`
  ADD PRIMARY KEY (`prid`);

--
-- Indexes for table `pmo_reports_efiles`
--
ALTER TABLE `pmo_reports_efiles`
  ADD PRIMARY KEY (`rfid`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `procurements`
--
ALTER TABLE `procurements`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `procurement_modes`
--
ALTER TABLE `procurement_modes`
  ADD PRIMARY KEY (`pmid`);

--
-- Indexes for table `procurement_requirements`
--
ALTER TABLE `procurement_requirements`
  ADD PRIMARY KEY (`prid`);

--
-- Indexes for table `procurement_types`
--
ALTER TABLE `procurement_types`
  ADD PRIMARY KEY (`ptid`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `psipop`
--
ALTER TABLE `psipop`
  ADD PRIMARY KEY (`psid`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`qid`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `reports_efiles`
--
ALTER TABLE `reports_efiles`
  ADD PRIMARY KEY (`rfid`);

--
-- Indexes for table `reports_types`
--
ALTER TABLE `reports_types`
  ADD PRIMARY KEY (`rtid`);

--
-- Indexes for table `requirements`
--
ALTER TABLE `requirements`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `requirements_efiles`
--
ALTER TABLE `requirements_efiles`
  ADD PRIMARY KEY (`rfid`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `routes_details`
--
ALTER TABLE `routes_details`
  ADD PRIMARY KEY (`rdid`);

--
-- Indexes for table `routings`
--
ALTER TABLE `routings`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `salary_grades`
--
ALTER TABLE `salary_grades`
  ADD PRIMARY KEY (`sgid`);

--
-- Indexes for table `salary_periods`
--
ALTER TABLE `salary_periods`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `salary_tranches`
--
ALTER TABLE `salary_tranches`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `ssls`
--
ALTER TABLE `ssls`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `tc_areas`
--
ALTER TABLE `tc_areas`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `tc_classes`
--
ALTER TABLE `tc_classes`
  ADD PRIMARY KEY (`tcid`);

--
-- Indexes for table `tc_committees`
--
ALTER TABLE `tc_committees`
  ADD PRIMARY KEY (`comm_id`);

--
-- Indexes for table `tc_officers`
--
ALTER TABLE `tc_officers`
  ADD PRIMARY KEY (`oid`);

--
-- Indexes for table `tc_positions`
--
ALTER TABLE `tc_positions`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `tc_sectors`
--
ALTER TABLE `tc_sectors`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `tc_trainings`
--
ALTER TABLE `tc_trainings`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `tc_transactions`
--
ALTER TABLE `tc_transactions`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `towns`
--
ALTER TABLE `towns`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `towns_backup`
--
ALTER TABLE `towns_backup`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `trainings`
--
ALTER TABLE `trainings`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `tranches`
--
ALTER TABLE `tranches`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `transactions_efiles`
--
ALTER TABLE `transactions_efiles`
  ADD PRIMARY KEY (`tfid`);

--
-- Indexes for table `transactions_requirements`
--
ALTER TABLE `transactions_requirements`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users_accesses`
--
ALTER TABLE `users_accesses`
  ADD PRIMARY KEY (`ua_id`);

--
-- Indexes for table `year_months`
--
ALTER TABLE `year_months`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accesses`
--
ALTER TABLE `accesses`
  MODIFY `aid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `accesses_pages`
--
ALTER TABLE `accesses_pages`
  MODIFY `ap_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `aid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activities_people`
--
ALTER TABLE `activities_people`
  MODIFY `apid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agencies`
--
ALTER TABLE `agencies`
  MODIFY `agency_id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `aid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `aid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brgys`
--
ALTER TABLE `brgys`
  MODIFY `bid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cgs_efiles`
--
ALTER TABLE `cgs_efiles`
  MODIFY `cfid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cocs`
--
ALTER TABLE `cocs`
  MODIFY `cid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `communications`
--
ALTER TABLE `communications`
  MODIFY `cid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `communications_efiles`
--
ALTER TABLE `communications_efiles`
  MODIFY `cfid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives`
--
ALTER TABLE `cooperatives`
  MODIFY `cid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_amendments`
--
ALTER TABLE `cooperatives_amendments`
  MODIFY `aid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_backup`
--
ALTER TABLE `cooperatives_backup`
  MODIFY `cid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_businesses`
--
ALTER TABLE `cooperatives_businesses`
  MODIFY `bid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_capitalizations`
--
ALTER TABLE `cooperatives_capitalizations`
  MODIFY `cfid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_cda`
--
ALTER TABLE `cooperatives_cda`
  MODIFY `ccid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_cgs`
--
ALTER TABLE `cooperatives_cgs`
  MODIFY `ccid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_classes`
--
ALTER TABLE `cooperatives_classes`
  MODIFY `ccid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_donations`
--
ALTER TABLE `cooperatives_donations`
  MODIFY `did` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_financials`
--
ALTER TABLE `cooperatives_financials`
  MODIFY `cfid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_governances`
--
ALTER TABLE `cooperatives_governances`
  MODIFY `gid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_loans`
--
ALTER TABLE `cooperatives_loans`
  MODIFY `lid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_logs`
--
ALTER TABLE `cooperatives_logs`
  MODIFY `lid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_members`
--
ALTER TABLE `cooperatives_members`
  MODIFY `mid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_members2`
--
ALTER TABLE `cooperatives_members2`
  MODIFY `mid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_memberships`
--
ALTER TABLE `cooperatives_memberships`
  MODIFY `cmid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_surplus`
--
ALTER TABLE `cooperatives_surplus`
  MODIFY `cfid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_trainings`
--
ALTER TABLE `cooperatives_trainings`
  MODIFY `ctid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_units`
--
ALTER TABLE `cooperatives_units`
  MODIFY `cuid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives_units2`
--
ALTER TABLE `cooperatives_units2`
  MODIFY `uid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deductions`
--
ALTER TABLE `deductions`
  MODIFY `did` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `did` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `did` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `efiles`
--
ALTER TABLE `efiles`
  MODIFY `eid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `efiles_files`
--
ALTER TABLE `efiles_files`
  MODIFY `ef_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `eid` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_banks`
--
ALTER TABLE `employees_banks`
  MODIFY `bid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_children`
--
ALTER TABLE `employees_children`
  MODIFY `cid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_credits`
--
ALTER TABLE `employees_credits`
  MODIFY `lid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_eligibilities`
--
ALTER TABLE `employees_eligibilities`
  MODIFY `eeid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_experiences`
--
ALTER TABLE `employees_experiences`
  MODIFY `eeid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_families`
--
ALTER TABLE `employees_families`
  MODIFY `fid` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_hirings`
--
ALTER TABLE `employees_hirings`
  MODIFY `hid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_info`
--
ALTER TABLE `employees_info`
  MODIFY `iid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_pds`
--
ALTER TABLE `employees_pds`
  MODIFY `pid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_promotions`
--
ALTER TABLE `employees_promotions`
  MODIFY `epid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_ratings`
--
ALTER TABLE `employees_ratings`
  MODIFY `rid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_schools`
--
ALTER TABLE `employees_schools`
  MODIFY `sid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_separations`
--
ALTER TABLE `employees_separations`
  MODIFY `sid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_trainings`
--
ALTER TABLE `employees_trainings`
  MODIFY `tid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees_voluntaries`
--
ALTER TABLE `employees_voluntaries`
  MODIFY `wid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses_accounts`
--
ALTER TABLE `expenses_accounts`
  MODIFY `eid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_types`
--
ALTER TABLE `file_types`
  MODIFY `tid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `franchises`
--
ALTER TABLE `franchises`
  MODIFY `fid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `franchises_ltfrb`
--
ALTER TABLE `franchises_ltfrb`
  MODIFY `fid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `franchises_ltfrb2`
--
ALTER TABLE `franchises_ltfrb2`
  MODIFY `fid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internals_dms`
--
ALTER TABLE `internals_dms`
  MODIFY `did` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internals_leave_applications`
--
ALTER TABLE `internals_leave_applications`
  MODIFY `aid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internals_memo_circulars`
--
ALTER TABLE `internals_memo_circulars`
  MODIFY `cid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internals_memo_orders`
--
ALTER TABLE `internals_memo_orders`
  MODIFY `oid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internals_memo_orders_employees`
--
ALTER TABLE `internals_memo_orders_employees`
  MODIFY `me_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internals_office_orders`
--
ALTER TABLE `internals_office_orders`
  MODIFY `oid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internals_special_orders`
--
ALTER TABLE `internals_special_orders`
  MODIFY `sid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internals_travel_orders`
--
ALTER TABLE `internals_travel_orders`
  MODIFY `tid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internals_travel_orders_employees`
--
ALTER TABLE `internals_travel_orders_employees`
  MODIFY `te_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `iid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_conversions`
--
ALTER TABLE `leave_conversions`
  MODIFY `cid` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `lid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201807`
--
ALTER TABLE `logs_201807`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201808`
--
ALTER TABLE `logs_201808`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201809`
--
ALTER TABLE `logs_201809`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201810`
--
ALTER TABLE `logs_201810`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201811`
--
ALTER TABLE `logs_201811`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201812`
--
ALTER TABLE `logs_201812`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201901`
--
ALTER TABLE `logs_201901`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201902`
--
ALTER TABLE `logs_201902`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201903`
--
ALTER TABLE `logs_201903`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201904`
--
ALTER TABLE `logs_201904`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201905`
--
ALTER TABLE `logs_201905`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201906`
--
ALTER TABLE `logs_201906`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201907`
--
ALTER TABLE `logs_201907`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201908`
--
ALTER TABLE `logs_201908`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201909`
--
ALTER TABLE `logs_201909`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201910`
--
ALTER TABLE `logs_201910`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201911`
--
ALTER TABLE `logs_201911`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_201912`
--
ALTER TABLE `logs_201912`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_202001`
--
ALTER TABLE `logs_202001`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_202002`
--
ALTER TABLE `logs_202002`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_202003`
--
ALTER TABLE `logs_202003`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_202004`
--
ALTER TABLE `logs_202004`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_202508`
--
ALTER TABLE `logs_202508`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `page_id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `pid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls_adjustments`
--
ALTER TABLE `payrolls_adjustments`
  MODIFY `aid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls_details`
--
ALTER TABLE `payrolls_details`
  MODIFY `pd_id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls_groups`
--
ALTER TABLE `payrolls_groups`
  MODIFY `gid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls_jocos`
--
ALTER TABLE `payrolls_jocos`
  MODIFY `jid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls_regular`
--
ALTER TABLE `payrolls_regular`
  MODIFY `rid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls_regular2`
--
ALTER TABLE `payrolls_regular2`
  MODIFY `rid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pmo_offices`
--
ALTER TABLE `pmo_offices`
  MODIFY `oid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pmo_reports`
--
ALTER TABLE `pmo_reports`
  MODIFY `rid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pmo_reports_details`
--
ALTER TABLE `pmo_reports_details`
  MODIFY `prid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pmo_reports_efiles`
--
ALTER TABLE `pmo_reports_efiles`
  MODIFY `rfid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `pid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurements`
--
ALTER TABLE `procurements`
  MODIFY `pid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement_modes`
--
ALTER TABLE `procurement_modes`
  MODIFY `pmid` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement_requirements`
--
ALTER TABLE `procurement_requirements`
  MODIFY `prid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement_types`
--
ALTER TABLE `procurement_types`
  MODIFY `ptid` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `pid` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `psipop`
--
ALTER TABLE `psipop`
  MODIFY `psid` int(3) NOT NULL AUTO_INCREMENT COMMENT 'pk';

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `qid` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `rid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `rid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports_efiles`
--
ALTER TABLE `reports_efiles`
  MODIFY `rfid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports_types`
--
ALTER TABLE `reports_types`
  MODIFY `rtid` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requirements`
--
ALTER TABLE `requirements`
  MODIFY `rid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requirements_efiles`
--
ALTER TABLE `requirements_efiles`
  MODIFY `rfid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `rid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `routes_details`
--
ALTER TABLE `routes_details`
  MODIFY `rdid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `routings`
--
ALTER TABLE `routings`
  MODIFY `rid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_grades`
--
ALTER TABLE `salary_grades`
  MODIFY `sgid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_periods`
--
ALTER TABLE `salary_periods`
  MODIFY `sid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_tranches`
--
ALTER TABLE `salary_tranches`
  MODIFY `sid` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ssls`
--
ALTER TABLE `ssls`
  MODIFY `sid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `sid` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tc_areas`
--
ALTER TABLE `tc_areas`
  MODIFY `aid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tc_classes`
--
ALTER TABLE `tc_classes`
  MODIFY `tcid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tc_committees`
--
ALTER TABLE `tc_committees`
  MODIFY `comm_id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tc_officers`
--
ALTER TABLE `tc_officers`
  MODIFY `oid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tc_positions`
--
ALTER TABLE `tc_positions`
  MODIFY `pid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tc_sectors`
--
ALTER TABLE `tc_sectors`
  MODIFY `sid` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tc_trainings`
--
ALTER TABLE `tc_trainings`
  MODIFY `tid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tc_transactions`
--
ALTER TABLE `tc_transactions`
  MODIFY `tid` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `towns`
--
ALTER TABLE `towns`
  MODIFY `tid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `towns_backup`
--
ALTER TABLE `towns_backup`
  MODIFY `tid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainings`
--
ALTER TABLE `trainings`
  MODIFY `tid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tranches`
--
ALTER TABLE `tranches`
  MODIFY `tid` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `tid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions_efiles`
--
ALTER TABLE `transactions_efiles`
  MODIFY `tfid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions_requirements`
--
ALTER TABLE `transactions_requirements`
  MODIFY `rid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_accesses`
--
ALTER TABLE `users_accesses`
  MODIFY `ua_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `year_months`
--
ALTER TABLE `year_months`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
