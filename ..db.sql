-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2024 at 01:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `benso_cloud`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `in_time` int(11) DEFAULT NULL,
  `in_latitude` varchar(255) DEFAULT NULL,
  `in_longitude` varchar(255) DEFAULT NULL,
  `out_time` int(11) DEFAULT NULL,
  `out_latitude` varchar(255) DEFAULT NULL,
  `out_longitude` varchar(255) DEFAULT NULL,
  `total_in_time` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_passing`
--

CREATE TABLE `bill_passing` (
  `id` int(11) NOT NULL,
  `bill_type` varchar(25) DEFAULT NULL,
  `entry_number` varchar(25) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `bill_number` varchar(25) DEFAULT NULL,
  `bill_id` int(11) DEFAULT NULL,
  `bill_from` varchar(25) DEFAULT NULL,
  `bill_status` varchar(25) NOT NULL DEFAULT 'Passed',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_passing_det`
--

CREATE TABLE `bill_passing_det` (
  `id` int(11) NOT NULL,
  `bill_passing_id` int(11) DEFAULT NULL COMMENT 'bill_passing',
  `cost_generation_det` int(11) DEFAULT NULL COMMENT 'cost_generation_det',
  `bill_rate` float(10,2) DEFAULT NULL,
  `bill_qty` int(11) DEFAULT NULL,
  `bill_amount` float(10,2) DEFAULT NULL,
  `debit_qty` int(11) DEFAULT NULL,
  `debit_amount` float(10,2) DEFAULT NULL,
  `bill_receipt_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_receipt`
--

CREATE TABLE `bill_receipt` (
  `id` int(11) NOT NULL,
  `bill_type` varchar(25) DEFAULT NULL,
  `cost_id` int(11) DEFAULT NULL,
  `entry_number` varchar(25) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `bill_number` varchar(255) DEFAULT NULL,
  `bill_date` date DEFAULT NULL,
  `supplier` int(11) DEFAULT NULL,
  `bill_amount` float(10,2) DEFAULT NULL,
  `bill_image` varchar(255) DEFAULT NULL,
  `approved_image` varchar(255) DEFAULT NULL,
  `comments` longtext DEFAULT NULL,
  `status` varchar(25) DEFAULT 'pending',
  `approval_status` varchar(25) DEFAULT 'pending',
  `approval_message` longtext DEFAULT NULL,
  `paid_amt` float(10,2) DEFAULT NULL,
  `payment_status` varchar(25) DEFAULT 'not paid',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `brand_code` varchar(255) DEFAULT NULL,
  `approvals` longtext DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `can_delete` varchar(255) DEFAULT 'yes',
  `is_active` varchar(55) DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget_process`
--

CREATE TABLE `budget_process` (
  `id` int(11) NOT NULL,
  `so_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `budget_for` varchar(255) DEFAULT NULL,
  `bud_type` varchar(25) DEFAULT NULL,
  `department` int(11) DEFAULT NULL COMMENT 'department',
  `category` int(11) DEFAULT NULL COMMENT 'category',
  `process` int(11) DEFAULT NULL COMMENT 'process',
  `budget_process_type` varchar(255) DEFAULT NULL,
  `yarn_id` int(11) DEFAULT NULL COMMENT 'mas_yarn',
  `req_wt` varchar(255) DEFAULT NULL,
  `fabric` int(11) DEFAULT NULL COMMENT 'fabric',
  `dyeing_color` int(11) DEFAULT NULL COMMENT 'color',
  `accessories` int(11) DEFAULT NULL COMMENT 'mas_accessories',
  `rate` float(10,2) DEFAULT NULL,
  `revised_rate` float(10,2) DEFAULT NULL,
  `rework_rate` float(10,2) DEFAULT NULL,
  `is_approved` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `requirement_id` int(11) DEFAULT NULL COMMENT '	fabric_requirements',
  `scanning_type` varchar(25) NOT NULL DEFAULT 'Piece'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget_process_partial`
--

CREATE TABLE `budget_process_partial` (
  `id` int(11) NOT NULL,
  `budget_process` int(11) DEFAULT NULL COMMENT 'budget_process',
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order_id',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `bud_type` varchar(25) DEFAULT NULL,
  `sod_combo` int(11) DEFAULT NULL COMMENT 'sod_combo',
  `sod_part` int(11) DEFAULT NULL COMMENT 'sod_part',
  `process` int(11) DEFAULT NULL COMMENT 'process',
  `rate` float(10,2) DEFAULT NULL,
  `revised_rate` float(10,2) DEFAULT NULL,
  `rework_rate` float(10,2) DEFAULT NULL,
  `is_approved` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget_subprocess`
--

CREATE TABLE `budget_subprocess` (
  `id` int(11) NOT NULL,
  `so_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `department` int(11) DEFAULT NULL COMMENT 'department',
  `category` int(11) DEFAULT NULL COMMENT 'category',
  `process` int(11) DEFAULT NULL COMMENT 'process',
  `subprocess` int(11) DEFAULT NULL COMMENT 'sub_process',
  `price` float(10,2) DEFAULT NULL,
  `is_approved` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bundle_details`
--

CREATE TABLE `bundle_details` (
  `id` int(11) NOT NULL,
  `cutting_barcode_id` int(11) DEFAULT NULL COMMENT 'cutting_barcode',
  `lay_length` varchar(25) DEFAULT NULL,
  `entry_num` varchar(25) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `sod_combo` int(11) DEFAULT NULL COMMENT 'sod_combo',
  `sod_part` int(11) DEFAULT NULL COMMENT 'sod_part',
  `sod_size` int(11) DEFAULT NULL COMMENT 'sod_size',
  `combo` int(11) DEFAULT NULL COMMENT 'color',
  `part` int(11) DEFAULT NULL COMMENT 'part',
  `color` int(11) DEFAULT NULL COMMENT 'color',
  `variation_value` int(11) DEFAULT NULL COMMENT 'variation_value',
  `order_qty` int(11) DEFAULT NULL,
  `cutting_qty` int(11) DEFAULT NULL,
  `pcs_per_bundle` int(11) DEFAULT NULL,
  `total_bundle` int(11) DEFAULT NULL,
  `bundle_number` int(11) DEFAULT NULL,
  `boundle_qr` varchar(255) DEFAULT NULL,
  `boundle_qrImage` varchar(255) DEFAULT NULL,
  `in_proseccing` varchar(25) DEFAULT NULL,
  `in_proseccing_id` int(11) DEFAULT NULL COMMENT 'processing_list',
  `in_proseccing_date` date DEFAULT NULL,
  `complete_processing` varchar(25) DEFAULT NULL,
  `complete_processing_date` date DEFAULT NULL,
  `in_sewing` varchar(25) DEFAULT NULL,
  `in_sewing_id` int(11) DEFAULT NULL COMMENT 'processing_list',
  `input_type` varchar(25) DEFAULT NULL,
  `line` int(11) DEFAULT NULL,
  `in_sewing_date` date DEFAULT NULL,
  `complete_sewing` varchar(25) DEFAULT NULL,
  `tot_sewingout` int(11) DEFAULT NULL,
  `s_out_complete` longtext DEFAULT NULL,
  `s_out_not_complete` longtext DEFAULT NULL,
  `comp_sewing_date` date DEFAULT NULL,
  `checking_complete` varchar(25) DEFAULT NULL,
  `checking_id` int(11) DEFAULT NULL,
  `checking_employee` int(11) DEFAULT NULL,
  `checking_date` date DEFAULT NULL,
  `tot_good_pcs` int(11) DEFAULT NULL,
  `ch_good_pcs` longtext DEFAULT NULL,
  `ch_missing_pcs` longtext DEFAULT NULL,
  `ch_reject_pcs` longtext DEFAULT NULL,
  `ch_rework_pcs` longtext DEFAULT NULL,
  `ch_rework_stage` varchar(255) DEFAULT NULL,
  `tot_checking` longtext DEFAULT NULL,
  `ironing_complete` varchar(25) DEFAULT NULL,
  `ironed_pieces` longtext DEFAULT NULL,
  `tot_ironing` longtext DEFAULT NULL,
  `packed_pieces` longtext DEFAULT NULL,
  `tot_packing` longtext DEFAULT NULL,
  `component_process` longtext DEFAULT NULL,
  `garment_process` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `date` date DEFAULT NULL,
  `is_dispatch` varchar(25) NOT NULL DEFAULT 'no'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bundle_piece_details`
--

CREATE TABLE `bundle_piece_details` (
  `id` int(11) NOT NULL,
  `bundle_detail_id` int(11) DEFAULT NULL,
  `piece_qr` varchar(255) DEFAULT NULL,
  `piece_qrImage` varchar(255) DEFAULT NULL,
  `in_proseccing` varchar(25) DEFAULT NULL,
  `is_inwarded` varchar(25) DEFAULT NULL,
  `in_sewing` varchar(25) DEFAULT NULL,
  `sewing_input` varchar(25) DEFAULT NULL,
  `sewing_output` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `can_delete` varchar(55) DEFAULT 'yes',
  `is_active` varchar(55) DEFAULT 'active',
  `create_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checking_list`
--

CREATE TABLE `checking_list` (
  `id` int(11) NOT NULL,
  `entry_num` int(11) NOT NULL,
  `entry_date` int(11) NOT NULL,
  `employee` int(11) NOT NULL,
  `process_id` int(11) DEFAULT NULL,
  `bundle_id` int(11) DEFAULT NULL,
  `good_pcs` int(11) DEFAULT NULL,
  `rework_pcs` int(11) DEFAULT NULL,
  `rejection_pcs` int(11) DEFAULT NULL,
  `rework_stage` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checking_output`
--

CREATE TABLE `checking_output` (
  `id` int(11) NOT NULL,
  `processing_list_id` int(11) DEFAULT NULL,
  `bundle_id` int(11) DEFAULT NULL,
  `checking_type` int(11) DEFAULT NULL,
  `pieces` longtext DEFAULT NULL,
  `completed_pcs` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `cities_name` varchar(30) NOT NULL,
  `state_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `color`
--

CREATE TABLE `color` (
  `id` int(11) NOT NULL,
  `color_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `common_comments`
--

CREATE TABLE `common_comments` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `comment_from` varchar(255) DEFAULT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `primary_id` int(11) DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL,
  `comment` varbinary(255) DEFAULT NULL,
  `creaed_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_code` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `phone1` varchar(255) DEFAULT NULL,
  `phone2` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `gst_no` varchar(255) DEFAULT NULL,
  `pan_no` varchar(255) DEFAULT NULL,
  `other` varchar(255) DEFAULT NULL,
  `so_caption` varchar(255) DEFAULT NULL,
  `mail_id` varchar(255) DEFAULT NULL,
  `account_info` varchar(255) DEFAULT NULL,
  `ex_info` longtext DEFAULT NULL,
  `in_time` time DEFAULT NULL,
  `out_time` time DEFAULT NULL,
  `working_hr` time DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `constants`
--

CREATE TABLE `constants` (
  `id` int(11) NOT NULL,
  `const_name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_user` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cost_generation`
--

CREATE TABLE `cost_generation` (
  `id` int(11) NOT NULL,
  `entry_number` varchar(25) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `employee` int(11) DEFAULT NULL,
  `is_receipted` varchar(25) DEFAULT 'no',
  `receipt_ref` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cost_generation_det`
--

CREATE TABLE `cost_generation_det` (
  `id` int(11) NOT NULL,
  `cost_generation_id` int(11) DEFAULT NULL COMMENT 'cost_generation',
  `entry_number` varchar(25) DEFAULT NULL,
  `employee` int(11) DEFAULT NULL COMMENT 'employee_detail',
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `sod_part` int(11) DEFAULT NULL COMMENT 'sod_part',
  `combo` int(11) DEFAULT NULL COMMENT 'color',
  `part` int(11) DEFAULT NULL COMMENT 'part',
  `color` int(11) DEFAULT NULL COMMENT 'color',
  `process` int(11) DEFAULT NULL COMMENT 'process',
  `max_qty` int(11) DEFAULT NULL,
  `bill_qty` int(11) DEFAULT NULL,
  `max_rate` float(10,2) DEFAULT NULL,
  `bill_rate` float(10,2) DEFAULT NULL,
  `bill_amount` float(10,2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `n_bill_rate` float(10,2) DEFAULT NULL,
  `n_bill_qty` int(11) DEFAULT NULL,
  `n_bill_amount` float(10,2) DEFAULT NULL,
  `debit_qty` int(11) DEFAULT NULL,
  `debit_amount` float(10,2) DEFAULT NULL,
  `bill_receipt_id` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_unit` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_code` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `phone1` varchar(255) DEFAULT NULL,
  `phone2` varchar(255) DEFAULT NULL,
  `emailid` varchar(255) DEFAULT NULL,
  `gst_no` varchar(255) DEFAULT NULL,
  `brand` int(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `wedd_date` date DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cutting_barcode`
--

CREATE TABLE `cutting_barcode` (
  `id` int(11) NOT NULL,
  `lay_number` int(11) DEFAULT NULL,
  `from_bno` int(11) DEFAULT NULL,
  `to_bno` int(11) DEFAULT NULL,
  `entry_number` varchar(25) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `sod_combo` int(11) DEFAULT NULL COMMENT 'sod_combo',
  `sod_part` int(11) DEFAULT NULL COMMENT 'sod_part',
  `combo_id` int(11) DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `employee` int(11) DEFAULT NULL COMMENT 'employee_detail',
  `fabric` int(11) DEFAULT NULL COMMENT 'fabric',
  `fabric_color` int(11) DEFAULT NULL COMMENT 'color',
  `fabric_lot` varchar(255) DEFAULT NULL,
  `gsm` varchar(255) DEFAULT NULL,
  `dia` varchar(255) DEFAULT NULL,
  `wt` varchar(255) DEFAULT NULL,
  `lay_length` varchar(255) DEFAULT NULL,
  `per_lay_no` varchar(255) DEFAULT NULL,
  `per_day_wt` varchar(255) DEFAULT NULL,
  `no_of_lay` varchar(255) DEFAULT NULL,
  `total_taken_wt` varchar(255) DEFAULT NULL,
  `reject_wt` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cutting_partial_planning`
--

CREATE TABLE `cutting_partial_planning` (
  `id` int(11) NOT NULL,
  `process_planing_id` int(11) DEFAULT NULL COMMENT 'process_planing',
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `type` varchar(25) DEFAULT NULL,
  `sod_part` mediumint(11) DEFAULT NULL COMMENT 'sod_part',
  `combo_part_qty` int(11) DEFAULT NULL,
  `sod_size` int(11) DEFAULT NULL COMMENT 'sod_size',
  `size_order_qty` int(11) DEFAULT NULL,
  `size_plan_qty` int(11) DEFAULT NULL,
  `plan_for` varchar(25) DEFAULT NULL,
  `plan_for_to` int(11) DEFAULT NULL,
  `combo_part` int(11) NOT NULL,
  `combo_size` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `department_name` varchar(255) DEFAULT NULL,
  `hod` int(11) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_detail`
--

CREATE TABLE `employee_detail` (
  `id` int(11) NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `user_group` int(11) DEFAULT NULL,
  `company` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `employee_code` varchar(255) DEFAULT NULL,
  `employee_photo` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `designation` int(11) DEFAULT NULL,
  `address1_com` varchar(255) DEFAULT NULL,
  `address2_com` varchar(255) DEFAULT NULL,
  `area_com` varchar(255) DEFAULT NULL,
  `pincode_com` varchar(255) DEFAULT NULL,
  `country_com` int(11) DEFAULT NULL,
  `state_com` int(11) DEFAULT NULL,
  `city_com` int(11) DEFAULT NULL,
  `address1_per` varchar(255) DEFAULT NULL,
  `address2_per` varchar(255) DEFAULT NULL,
  `area_per` varchar(255) DEFAULT NULL,
  `pincode_per` varchar(255) DEFAULT NULL,
  `country_per` int(11) DEFAULT NULL,
  `state_per` int(11) DEFAULT NULL,
  `city_per` int(11) DEFAULT NULL,
  `aadhar_card` varchar(255) DEFAULT NULL,
  `pan_card` varchar(255) DEFAULT NULL,
  `license` varchar(255) DEFAULT NULL,
  `other_docs` varchar(255) DEFAULT NULL,
  `acc_holder_name` varchar(255) DEFAULT NULL,
  `acc_num` varchar(255) DEFAULT NULL,
  `ifsc` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_branch` varchar(255) DEFAULT NULL,
  `basic_salary` float(10,2) DEFAULT NULL,
  `house_rent` float(10,2) DEFAULT NULL,
  `pf` float(10,2) DEFAULT NULL,
  `esi` float(10,2) DEFAULT NULL,
  `salary_total` float(10,2) DEFAULT NULL,
  `basic_salary_cmpl` float(10,2) DEFAULT NULL,
  `house_rent_cmpl` float(10,2) DEFAULT NULL,
  `pf_cmpl` float(10,2) DEFAULT NULL,
  `esi_cmpl` float(10,2) DEFAULT NULL,
  `salary_total_cmpl` float(10,2) DEFAULT NULL,
  `task_remainder_level` varchar(255) DEFAULT NULL,
  `sub_billname` varchar(255) DEFAULT NULL,
  `is_cg` varchar(25) DEFAULT 'no',
  `cg_name` varchar(255) DEFAULT NULL,
  `process` int(11) DEFAULT NULL,
  `is_active` varchar(20) DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_detail_temp`
--

CREATE TABLE `employee_detail_temp` (
  `id` int(11) NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `company` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `employee_code` varchar(255) DEFAULT NULL,
  `employee_photo` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `designation` int(11) DEFAULT NULL,
  `address1_com` varchar(255) DEFAULT NULL,
  `address2_com` varchar(255) DEFAULT NULL,
  `area_com` varchar(255) DEFAULT NULL,
  `pincode_com` varchar(255) DEFAULT NULL,
  `country_com` int(11) DEFAULT NULL,
  `state_com` int(11) DEFAULT NULL,
  `city_com` int(11) DEFAULT NULL,
  `address1_per` varchar(255) DEFAULT NULL,
  `address2_per` varchar(255) DEFAULT NULL,
  `area_per` varchar(255) DEFAULT NULL,
  `pincode_per` varchar(255) DEFAULT NULL,
  `country_per` int(11) DEFAULT NULL,
  `state_per` int(11) DEFAULT NULL,
  `city_per` int(11) DEFAULT NULL,
  `aadhar_card` varchar(255) DEFAULT NULL,
  `pan_card` varchar(255) DEFAULT NULL,
  `license` varchar(255) DEFAULT NULL,
  `other_docs` varchar(255) DEFAULT NULL,
  `acc_holder_name` varchar(255) DEFAULT NULL,
  `acc_num` varchar(255) DEFAULT NULL,
  `ifsc` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_branch` varchar(255) DEFAULT NULL,
  `is_active` varchar(20) DEFAULT 'active',
  `is_approved` varchar(255) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `approved_notes` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_main`
--

CREATE TABLE `expense_main` (
  `id` int(11) NOT NULL,
  `expense_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL,
  `can_delete` varchar(10) NOT NULL DEFAULT 'yes',
  `type_exp` varchar(255) DEFAULT NULL,
  `type_vp` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_sub`
--

CREATE TABLE `expense_sub` (
  `id` int(11) NOT NULL,
  `full_name` int(11) DEFAULT NULL,
  `expense_sub` varchar(255) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabprogram_print`
--

CREATE TABLE `fabprogram_print` (
  `id` int(11) NOT NULL,
  `sales_order_detalis_id` int(11) DEFAULT NULL,
  `fabric_program_id` int(11) DEFAULT NULL,
  `fabric_type` varchar(25) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `process_order` int(11) DEFAULT NULL,
  `fabric_id` int(11) DEFAULT NULL,
  `dyeing_color` int(11) DEFAULT NULL,
  `yarn_mixing` varchar(255) DEFAULT NULL,
  `loss_per` int(11) DEFAULT NULL,
  `dia_wt` int(11) DEFAULT NULL,
  `req_wtt` float(10,2) DEFAULT NULL,
  `temp_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `aop_name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabprogram_print_yarn`
--

CREATE TABLE `fabprogram_print_yarn` (
  `id` int(11) NOT NULL,
  `sales_order_detalis_id` int(11) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `fabric_id` int(11) DEFAULT NULL,
  `dyeing_color` int(11) DEFAULT NULL,
  `yarn` int(11) DEFAULT NULL,
  `color` int(11) DEFAULT NULL,
  `mixing` int(11) DEFAULT NULL,
  `loss_per` int(11) DEFAULT NULL,
  `dia_wt` varchar(255) DEFAULT NULL,
  `req_wtt` float(10,2) DEFAULT NULL,
  `req_yarn_wt` float(10,2) DEFAULT NULL,
  `process_order` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric`
--

CREATE TABLE `fabric` (
  `id` int(11) NOT NULL,
  `fabric_name` varchar(255) DEFAULT NULL,
  `fabric_code` varchar(255) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_consumption`
--

CREATE TABLE `fabric_consumption` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `sales_order_detail_id` int(11) DEFAULT NULL COMMENT 'sales_order_detail',
  `sod_combo` varchar(25) DEFAULT NULL COMMENT 'sod_combo',
  `order_qty` int(11) DEFAULT NULL,
  `fabric` int(11) DEFAULT NULL COMMENT 'fabric',
  `gsm` int(11) DEFAULT NULL,
  `color` int(11) DEFAULT NULL COMMENT 'color',
  `component` int(11) DEFAULT NULL COMMENT 'mas_component',
  `finishing_dia` varchar(255) DEFAULT NULL,
  `pcs_wt` varchar(255) DEFAULT NULL,
  `req_wt` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_dc`
--

CREATE TABLE `fabric_dc` (
  `id` int(11) NOT NULL,
  `dc_number` varchar(25) DEFAULT NULL,
  `dc_date` date DEFAULT NULL,
  `supplier` int(11) DEFAULT NULL,
  `process` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_dc_det`
--

CREATE TABLE `fabric_dc_det` (
  `id` int(11) NOT NULL,
  `fabric_dc_id` int(11) DEFAULT NULL COMMENT 'fabric_dc',
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `fab_req_id` int(11) DEFAULT NULL COMMENT 'fabric_requirements',
  `fabric_id` int(11) DEFAULT NULL COMMENT 'fabric',
  `color_id` int(11) DEFAULT NULL COMMENT 'color',
  `output_wt` float(10,2) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL COMMENT 'process',
  `process_order` int(11) DEFAULT NULL,
  `yarn_id` int(11) DEFAULT NULL COMMENT 'mas_yarn',
  `mixing_per` int(11) DEFAULT NULL,
  `dc_balance` float(10,2) DEFAULT NULL,
  `stock` float(10,2) DEFAULT NULL,
  `bag_roll` varchar(255) DEFAULT NULL,
  `dc_qty_wt` float(10,2) DEFAULT NULL,
  `dc_inward_wt` float(10,2) DEFAULT NULL,
  `complete_inward` varchar(25) DEFAULT 'no'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_dc_inw`
--

CREATE TABLE `fabric_dc_inw` (
  `id` int(11) NOT NULL,
  `entry_number` varchar(25) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `supplier` int(11) DEFAULT NULL,
  `supplier_dc` varchar(255) DEFAULT NULL,
  `supplier_dc_date` date DEFAULT NULL,
  `fabric_dc` int(11) DEFAULT NULL,
  `inward_type` varchar(25) DEFAULT NULL,
  `inward_process` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_dc_inw_det`
--

CREATE TABLE `fabric_dc_inw_det` (
  `id` int(11) NOT NULL,
  `fabric_dc_inw` int(11) DEFAULT NULL,
  `fabric_dc_det_id` int(11) DEFAULT NULL COMMENT 'fabric_dc_det',
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `fab_req_id` int(11) DEFAULT NULL COMMENT 'fabric_requirements',
  `fabric_id` int(11) DEFAULT NULL COMMENT 'fabric',
  `process_id` int(11) DEFAULT NULL COMMENT 'process',
  `grn_number` varchar(255) DEFAULT NULL,
  `supp_dc_number` varchar(255) DEFAULT NULL COMMENT 'supplier',
  `supplier` int(11) DEFAULT NULL,
  `dc_qty` float(10,2) DEFAULT NULL,
  `inw_bag_roll` int(11) DEFAULT NULL,
  `inw_qty` float(10,2) DEFAULT NULL,
  `balance_qty` float(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_delivery`
--

CREATE TABLE `fabric_delivery` (
  `id` int(11) NOT NULL,
  `dc_number` varchar(25) DEFAULT NULL,
  `dc_date` date DEFAULT NULL,
  `delivery_type` varchar(25) DEFAULT NULL,
  `delivery_to` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_delivery_det`
--

CREATE TABLE `fabric_delivery_det` (
  `id` int(11) NOT NULL,
  `fabric_delivery` int(11) DEFAULT NULL,
  `sales_order_id` int(11) DEFAULT NULL,
  `sales_order_detail_id` int(11) DEFAULT NULL,
  `fabric` int(11) DEFAULT NULL,
  `fabric_consumption` int(11) DEFAULT NULL,
  `req_wt` varchar(25) DEFAULT NULL,
  `del_bal` varchar(25) DEFAULT NULL,
  `bag_roll` varchar(25) DEFAULT NULL,
  `del_wt` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_opening`
--

CREATE TABLE `fabric_opening` (
  `id` int(11) NOT NULL,
  `entry_number` varchar(25) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_opening_det`
--

CREATE TABLE `fabric_opening_det` (
  `id` int(11) NOT NULL,
  `fabric_opening_id` int(11) DEFAULT NULL COMMENT 'fabric_opening',
  `stock_bo` varchar(25) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `po_stage` int(11) DEFAULT NULL COMMENT 'process',
  `material_name` int(11) DEFAULT NULL COMMENT 'bo => fabric_requirements,\r\nstock => mas_stockitem',
  `po_balance` varchar(25) DEFAULT NULL,
  `stock_dia` int(11) DEFAULT NULL,
  `bag_roll` varchar(25) DEFAULT NULL,
  `opening_qty` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_po`
--

CREATE TABLE `fabric_po` (
  `id` int(11) NOT NULL,
  `entry_number` varchar(25) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `supplier` int(11) DEFAULT NULL,
  `grand_total` float(10,2) DEFAULT NULL,
  `ship_to` int(11) DEFAULT NULL,
  `receipt_complete` varchar(25) DEFAULT 'No',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_po_det`
--

CREATE TABLE `fabric_po_det` (
  `id` int(11) NOT NULL,
  `fab_po` int(11) DEFAULT NULL COMMENT 'fabric_po',
  `stock_bo` varchar(25) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `po_stage` int(11) DEFAULT NULL COMMENT 'process',
  `material_name` int(11) DEFAULT NULL COMMENT 'bo=>fabric_requirements, stock=>mas_stockitem',
  `color_ref` int(11) DEFAULT NULL COMMENT 'color',
  `bag_roll` varchar(255) DEFAULT NULL,
  `po_balance` float(10,2) DEFAULT NULL,
  `stock_dia` int(11) DEFAULT NULL,
  `po_qty_wt` float(10,2) DEFAULT NULL,
  `rate` float(10,2) DEFAULT NULL,
  `tax_per` int(11) DEFAULT NULL COMMENT 'tax_main',
  `rate_w_tax` float(10,2) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `received_bal` float(10,2) DEFAULT NULL,
  `received_bag` float(10,2) DEFAULT NULL,
  `received_qty` float(10,2) DEFAULT NULL,
  `complete_receipt` varchar(25) DEFAULT 'No'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_po_expense`
--

CREATE TABLE `fabric_po_expense` (
  `id` int(11) NOT NULL,
  `fabric_po` int(11) DEFAULT NULL,
  `expense_name` int(11) DEFAULT NULL,
  `expense_amount` float(10,2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_po_receipt`
--

CREATE TABLE `fabric_po_receipt` (
  `id` int(11) NOT NULL,
  `grn_number` varchar(255) DEFAULT NULL,
  `grn_date` date DEFAULT NULL,
  `supplier` int(11) DEFAULT NULL,
  `sup_dc_number` varchar(255) DEFAULT NULL,
  `sup_dc_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_po_receipt_det`
--

CREATE TABLE `fabric_po_receipt_det` (
  `id` int(11) NOT NULL,
  `fabric_po_receipt` int(11) DEFAULT NULL COMMENT 'fabric_po_receipt',
  `fabric_po_det` int(11) DEFAULT NULL COMMENT 'fabric_po_det',
  `fabric_requirements` int(11) DEFAULT NULL COMMENT 'fabric_requirements',
  `po_stage` int(11) DEFAULT NULL COMMENT 'process',
  `bag_roll` varchar(255) DEFAULT NULL,
  `po_qty_wt` varchar(255) DEFAULT NULL,
  `received_bal` varchar(255) DEFAULT NULL,
  `received_bag` varchar(245) DEFAULT NULL,
  `received_qty` varchar(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_requirements`
--

CREATE TABLE `fabric_requirements` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `style_id` int(11) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `process_order` int(11) DEFAULT NULL,
  `fabric_type` varchar(25) DEFAULT NULL,
  `fabric_id` int(11) DEFAULT NULL,
  `yarn_id` int(11) DEFAULT NULL,
  `yarn_mixing` longtext DEFAULT NULL,
  `loss_p` int(11) DEFAULT NULL,
  `color` int(11) DEFAULT NULL,
  `dia_size` int(11) DEFAULT NULL,
  `req_wt` float(10,2) DEFAULT NULL,
  `aop_name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabric_stock`
--

CREATE TABLE `fabric_stock` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `process_id` int(11) DEFAULT NULL COMMENT 'process',
  `process_order` int(11) DEFAULT NULL,
  `fabric_id` int(11) DEFAULT NULL COMMENT 'fabric',
  `yarn_id` int(11) DEFAULT NULL COMMENT 'yarn',
  `supplier` int(11) DEFAULT NULL COMMENT 'supplier',
  `stock_bo` varchar(25) DEFAULT NULL,
  `fabric_requirements` int(11) DEFAULT NULL COMMENT 'bo=>fabric_requirements, stock=>mas_stockitem',
  `requirements_from` int(11) DEFAULT NULL COMMENT 'fabric_requirements',
  `stock_from` varchar(255) DEFAULT NULL,
  `stock_id` int(11) DEFAULT NULL,
  `entry_grn_number` varchar(255) DEFAULT NULL,
  `req_bag_roll` varchar(255) DEFAULT NULL,
  `req_qty` varchar(255) DEFAULT NULL,
  `received_bag` varchar(255) DEFAULT NULL,
  `received_qty` varchar(255) DEFAULT NULL,
  `stock_qty` varchar(25) DEFAULT NULL,
  `stock_status` varchar(25) NOT NULL DEFAULT 'in_stock',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inhouse_daily_status`
--

CREATE TABLE `inhouse_daily_status` (
  `id` int(11) NOT NULL,
  `processing_id` int(11) DEFAULT NULL,
  `entry_number` varchar(25) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `scanType` varchar(25) DEFAULT NULL,
  `scanUsing` varchar(25) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inhouse_process`
--

CREATE TABLE `inhouse_process` (
  `id` int(11) NOT NULL,
  `processing_id` int(11) DEFAULT NULL,
  `daily_status_id` int(11) DEFAULT NULL,
  `bundle_id` int(11) DEFAULT NULL,
  `employee` int(11) DEFAULT NULL,
  `completed_qty` int(11) DEFAULT NULL,
  `completed_pcs` longtext DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inwarded_bundle`
--

CREATE TABLE `inwarded_bundle` (
  `id` int(11) NOT NULL,
  `processing_id` int(11) DEFAULT NULL,
  `bundle_id` int(11) DEFAULT NULL,
  `bundle_qr` varchar(255) DEFAULT NULL,
  `pieces_qr` longtext DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ironing`
--

CREATE TABLE `ironing` (
  `id` int(11) NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `scanning_using` varchar(255) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `style_id` int(11) DEFAULT NULL,
  `sod_part` int(11) DEFAULT NULL,
  `entry_number` varchar(25) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `assigned_emp` int(11) DEFAULT NULL,
  `piece_scanned` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ironing_detail`
--

CREATE TABLE `ironing_detail` (
  `id` int(11) NOT NULL,
  `ironing_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `style_id` int(11) DEFAULT NULL,
  `sod_combo` int(11) DEFAULT NULL,
  `sod_part` int(11) DEFAULT NULL,
  `sod_size` int(11) DEFAULT NULL,
  `combo_id` int(11) DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `variation_value` int(11) DEFAULT NULL,
  `bundleId` int(11) DEFAULT NULL,
  `order_qty` int(11) DEFAULT NULL,
  `cutting_qty` int(11) DEFAULT NULL,
  `ironing_qty` int(11) DEFAULT NULL,
  `piece_ironed` longtext DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `itemlist`
--

CREATE TABLE `itemlist` (
  `id` int(11) NOT NULL,
  `category` int(11) DEFAULT NULL,
  `brand` int(11) DEFAULT NULL,
  `item_code` varchar(255) DEFAULT NULL,
  `hsn_code` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `sales1` varchar(255) DEFAULT NULL,
  `sales2` varchar(255) DEFAULT NULL,
  `sales3` varchar(255) DEFAULT NULL,
  `gst` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `line_planning`
--

CREATE TABLE `line_planning` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `sod_part` int(11) DEFAULT NULL COMMENT 'sod_part',
  `sod_size` int(11) DEFAULT NULL COMMENT 'sod_size',
  `combo_id` int(11) DEFAULT NULL COMMENT 'color',
  `part_id` int(11) DEFAULT NULL COMMENT 'part',
  `color_id` int(11) DEFAULT NULL COMMENT 'color',
  `variation_value` int(11) DEFAULT NULL COMMENT 'variation_value',
  `planning_type` varchar(255) DEFAULT NULL,
  `order_qty` varchar(255) DEFAULT NULL,
  `size_qty` varchar(255) DEFAULT NULL,
  `plan_qty` int(11) DEFAULT NULL,
  `assign_type` varchar(25) DEFAULT NULL,
  `assign_to` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `line_planning_size`
--

CREATE TABLE `line_planning_size` (
  `id` int(11) NOT NULL,
  `line_planning_id` int(11) DEFAULT NULL COMMENT 'line_planning',
  `sod_size` int(11) DEFAULT NULL COMMENT 'sod_size',
  `variation_value` int(11) DEFAULT NULL COMMENT 'variation_value',
  `order_qty` int(11) DEFAULT NULL,
  `plan_qty` int(11) DEFAULT NULL,
  `assign_type` varchar(25) DEFAULT NULL,
  `assign_to` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `log_user` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machine`
--

CREATE TABLE `machine` (
  `id` int(11) NOT NULL,
  `machine_code` varchar(255) DEFAULT NULL,
  `machine_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_country`
--

CREATE TABLE `master_country` (
  `auto_number` int(15) NOT NULL,
  `country` varchar(100) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_accessories`
--

CREATE TABLE `mas_accessories` (
  `id` int(11) NOT NULL,
  `acc_type` int(11) DEFAULT NULL COMMENT 'mas_accessories_type',
  `acc_name` varchar(255) DEFAULT NULL,
  `excess` varchar(255) DEFAULT NULL,
  `purchase_uom` int(11) DEFAULT NULL COMMENT 'mas_uom',
  `consumption_uom` int(11) DEFAULT NULL COMMENT 'mas_uom',
  `purchase_unit` int(11) DEFAULT NULL,
  `uom_qty` varchar(255) DEFAULT NULL,
  `can_edit_delete` varchar(25) NOT NULL DEFAULT 'yes',
  `is_active` varchar(255) DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_accessories_type`
--

CREATE TABLE `mas_accessories_type` (
  `id` int(11) NOT NULL,
  `type_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(255) NOT NULL DEFAULT 'active',
  `created_by` int(11) NOT NULL,
  `created_unit` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_approval`
--

CREATE TABLE `mas_approval` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `daily_followup` varchar(255) NOT NULL,
  `end_followup` varchar(255) NOT NULL,
  `brand` int(11) DEFAULT NULL,
  `calculation_type` varchar(25) DEFAULT NULL,
  `start_day` int(11) DEFAULT NULL,
  `end_day` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `daily_followup_duration` varchar(255) DEFAULT NULL,
  `end_followup_duration` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_bank`
--

CREATE TABLE `mas_bank` (
  `id` int(50) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_no` varchar(255) NOT NULL,
  `bank_ifsc` varchar(255) NOT NULL,
  `bank_branch` varchar(255) NOT NULL,
  `bank_other` varchar(255) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_checking`
--

CREATE TABLE `mas_checking` (
  `id` int(11) NOT NULL,
  `checking_name` varchar(255) DEFAULT NULL,
  `is_rework` varchar(25) DEFAULT NULL,
  `checking_color` varchar(255) DEFAULT NULL,
  `is_active` varchar(25) DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_component`
--

CREATE TABLE `mas_component` (
  `id` int(11) NOT NULL,
  `component_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(25) DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_currency`
--

CREATE TABLE `mas_currency` (
  `id` int(11) NOT NULL,
  `currency_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci DEFAULT NULL,
  `currency_value` float(10,2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_defect`
--

CREATE TABLE `mas_defect` (
  `id` int(11) NOT NULL,
  `defect_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(11) DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_designation`
--

CREATE TABLE `mas_designation` (
  `id` int(11) NOT NULL,
  `desig_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(11) DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_line`
--

CREATE TABLE `mas_line` (
  `id` int(11) NOT NULL,
  `line_name` varchar(255) DEFAULT NULL,
  `process` longtext DEFAULT NULL,
  `pay_type` int(11) DEFAULT NULL COMMENT '1=> shift, 2=> Pcs Rate.',
  `cost_generator` longtext DEFAULT NULL,
  `is_active` varchar(25) NOT NULL DEFAULT 'active',
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_notes`
--

CREATE TABLE `mas_notes` (
  `id` int(50) NOT NULL,
  `note_name` varchar(255) DEFAULT NULL,
  `invoice_note` varchar(255) DEFAULT NULL,
  `note_name_multiple` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_pack`
--

CREATE TABLE `mas_pack` (
  `id` int(11) NOT NULL,
  `pack_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(25) DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_stockitem`
--

CREATE TABLE `mas_stockitem` (
  `id` int(11) NOT NULL,
  `fabric_type` varchar(30) DEFAULT NULL,
  `fabric_name` int(11) DEFAULT NULL,
  `yarn_mixing` longtext DEFAULT NULL,
  `gsm` varchar(255) DEFAULT NULL,
  `dying_color` int(11) DEFAULT NULL,
  `aop_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_task`
--

CREATE TABLE `mas_task` (
  `id` int(11) NOT NULL,
  `task_name` varchar(255) DEFAULT NULL,
  `task_type` varchar(255) NOT NULL,
  `tasksub_type` varchar(255) NOT NULL,
  `is_active` varchar(255) DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `task_process_id` int(11) DEFAULT NULL,
  `daily_followup_task` varchar(255) DEFAULT NULL,
  `daily_followup_duration_task` varchar(255) DEFAULT NULL,
  `end_followup_task` varchar(255) DEFAULT NULL,
  `end_followup_duration_task` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_uom`
--

CREATE TABLE `mas_uom` (
  `id` int(11) NOT NULL,
  `uom_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mas_yarn`
--

CREATE TABLE `mas_yarn` (
  `id` int(11) NOT NULL,
  `yarn_name` varchar(255) DEFAULT NULL,
  `yarn_code` varchar(255) DEFAULT NULL,
  `is_active` varchar(25) NOT NULL DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `merchand_detail`
--

CREATE TABLE `merchand_detail` (
  `id` int(11) NOT NULL,
  `merchand_name` int(11) DEFAULT NULL,
  `merchand_code` varchar(255) DEFAULT NULL,
  `merch_brand` longtext DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `mailid` varchar(255) DEFAULT NULL,
  `is_active` varchar(10) DEFAULT 'active',
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orbidx_checking`
--

CREATE TABLE `orbidx_checking` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `sod_combo` int(11) DEFAULT NULL COMMENT 'sod_combo',
  `sod_part` int(11) DEFAULT NULL COMMENT 'sod_part',
  `sod_size` int(11) DEFAULT NULL COMMENT 'sod_size',
  `combo` int(11) DEFAULT NULL COMMENT 'color',
  `part` int(11) DEFAULT NULL COMMENT 'part',
  `color` int(11) DEFAULT NULL COMMENT 'color',
  `process` int(11) DEFAULT NULL COMMENT 'process',
  `variation_value` int(11) DEFAULT NULL COMMENT 'variation_value',
  `scan_using` varchar(25) DEFAULT NULL,
  `scan_type` varchar(255) DEFAULT NULL,
  `mode` varchar(25) DEFAULT NULL,
  `bundle_details_id` int(11) DEFAULT NULL,
  `scanned_count` int(11) DEFAULT NULL,
  `piece_id` longtext DEFAULT NULL,
  `qr_code` longtext DEFAULT NULL,
  `device_name` varchar(255) DEFAULT NULL,
  `line` int(11) DEFAULT NULL,
  `device_user` int(11) DEFAULT NULL,
  `logUnit` int(11) DEFAULT NULL COMMENT 'company',
  `date` date DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orbidx_component_process`
--

CREATE TABLE `orbidx_component_process` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `sod_combo` int(11) DEFAULT NULL COMMENT 'sod_combo',
  `sod_part` int(11) DEFAULT NULL COMMENT 'sod_part',
  `sod_size` int(11) DEFAULT NULL COMMENT 'sod_size',
  `combo` int(11) DEFAULT NULL COMMENT 'color',
  `part` int(11) DEFAULT NULL COMMENT 'part',
  `color` int(11) DEFAULT NULL COMMENT 'color',
  `variation_value` int(11) DEFAULT NULL COMMENT 'variation_value',
  `department` int(11) DEFAULT NULL COMMENT 'department',
  `process` int(11) DEFAULT NULL COMMENT 'process',
  `scan_using` varchar(25) DEFAULT NULL,
  `scan_type` varchar(255) DEFAULT NULL,
  `bundle_details_id` int(11) DEFAULT NULL,
  `scanned_count` int(11) DEFAULT NULL,
  `piece_id` longtext DEFAULT NULL,
  `qr_code` longtext DEFAULT NULL,
  `device_name` varchar(255) DEFAULT NULL,
  `line` int(11) DEFAULT NULL,
  `device_user` int(11) DEFAULT NULL,
  `logUnit` int(11) DEFAULT NULL COMMENT 'company',
  `date` date DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orbidx_device`
--

CREATE TABLE `orbidx_device` (
  `id` int(11) NOT NULL,
  `device` varchar(255) NOT NULL,
  `department` int(11) NOT NULL,
  `process` int(11) NOT NULL,
  `scan_type` varchar(255) DEFAULT NULL,
  `line` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_unit` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orbidx_garment_process`
--

CREATE TABLE `orbidx_garment_process` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `sod_combo` int(11) DEFAULT NULL COMMENT 'sod_combo',
  `sod_part` int(11) DEFAULT NULL COMMENT 'sod_part',
  `sod_size` int(11) DEFAULT NULL COMMENT 'sod_size',
  `combo` int(11) DEFAULT NULL COMMENT 'color',
  `part` int(11) DEFAULT NULL COMMENT 'part',
  `color` int(11) DEFAULT NULL COMMENT 'color',
  `variation_value` int(11) DEFAULT NULL COMMENT 'variation_value',
  `department` int(11) DEFAULT NULL COMMENT 'department',
  `process` int(11) DEFAULT NULL COMMENT 'process',
  `scan_type` varchar(255) DEFAULT NULL,
  `bundle_details_id` int(11) DEFAULT NULL,
  `scanned_count` int(11) DEFAULT NULL,
  `piece_id` longtext DEFAULT NULL,
  `qr_code` longtext DEFAULT NULL,
  `device_name` varchar(255) DEFAULT NULL,
  `line` int(11) DEFAULT NULL,
  `device_user` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orbidx_sewingout`
--

CREATE TABLE `orbidx_sewingout` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `sod_combo` int(11) DEFAULT NULL COMMENT 'sod_combo',
  `sod_part` int(11) DEFAULT NULL COMMENT 'sod_part',
  `sod_size` int(11) DEFAULT NULL COMMENT 'sod_size',
  `combo` int(11) DEFAULT NULL COMMENT 'color',
  `part` int(11) DEFAULT NULL COMMENT 'part',
  `color` int(11) DEFAULT NULL COMMENT 'color',
  `process` int(11) DEFAULT NULL COMMENT 'process',
  `variation_value` int(11) DEFAULT NULL COMMENT 'variation_value',
  `scan_using` varchar(25) DEFAULT NULL,
  `scan_type` varchar(255) DEFAULT NULL,
  `bundle_details_id` int(11) DEFAULT NULL,
  `scanned_count` int(11) DEFAULT NULL,
  `piece_id` longtext DEFAULT NULL,
  `qr_code` longtext DEFAULT NULL,
  `device_name` varchar(255) DEFAULT NULL,
  `line` int(11) DEFAULT NULL,
  `device_user` int(11) DEFAULT NULL,
  `logUnit` int(11) DEFAULT NULL COMMENT 'company',
  `date` date DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_process`
--

CREATE TABLE `order_process` (
  `id` int(11) NOT NULL,
  `so_id` int(11) DEFAULT NULL,
  `budget_id` int(11) DEFAULT NULL,
  `budget_type` varchar(255) DEFAULT NULL,
  `type` varchar(55) DEFAULT NULL,
  `supplier` int(11) DEFAULT NULL,
  `process` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_tasks`
--

CREATE TABLE `order_tasks` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) NOT NULL,
  `time_management_template_det` int(11) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `task_date` date NOT NULL,
  `task_timeing` int(11) NOT NULL,
  `task_for` int(11) NOT NULL,
  `resp_b` longtext NOT NULL,
  `task_status` int(11) NOT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `task_proof` varchar(255) DEFAULT NULL,
  `completed_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_task_timer`
--

CREATE TABLE `order_task_timer` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `total_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packing`
--

CREATE TABLE `packing` (
  `id` int(11) NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `scanning_using` varchar(255) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `style_id` int(11) DEFAULT NULL,
  `sod_part` int(11) DEFAULT NULL,
  `entry_number` varchar(25) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `assigned_emp` int(11) DEFAULT NULL,
  `piece_scanned` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packing_detail`
--

CREATE TABLE `packing_detail` (
  `id` int(11) NOT NULL,
  `packing_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `style_id` int(11) DEFAULT NULL,
  `sod_combo` int(11) DEFAULT NULL,
  `sod_part` int(11) DEFAULT NULL,
  `sod_size` int(11) DEFAULT NULL,
  `combo_id` int(11) DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `variation_value` int(11) DEFAULT NULL,
  `bundleId` int(11) DEFAULT NULL,
  `order_qty` int(11) DEFAULT NULL,
  `cutting_qty` int(11) DEFAULT NULL,
  `ironing_qty` int(11) DEFAULT NULL,
  `packing_qty` int(11) DEFAULT NULL,
  `piece_packed` longtext DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `part`
--

CREATE TABLE `part` (
  `id` int(11) NOT NULL,
  `part_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(25) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `entry_number` varchar(255) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `bill_type` varchar(255) DEFAULT NULL,
  `supplier` int(11) DEFAULT NULL,
  `payment_type` varchar(25) DEFAULT NULL,
  `total_outstanding` float(10,2) DEFAULT NULL,
  `bill_ref` varchar(255) DEFAULT NULL,
  `bill_value` float(10,2) DEFAULT NULL,
  `pay_amount` float(10,2) DEFAULT NULL,
  `paid_excess` float(10,2) DEFAULT NULL,
  `pay_method` varchar(25) DEFAULT NULL,
  `pay_ref_file` varchar(255) DEFAULT NULL,
  `pay_ref_detail` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments_for_bill_receipts`
--

CREATE TABLE `payments_for_bill_receipts` (
  `id` int(11) NOT NULL,
  `bill_receipt_id` int(11) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `total` float(10,2) DEFAULT NULL,
  `paid` float(10,2) DEFAULT NULL,
  `due` float(10,2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pcs_checkingout`
--

CREATE TABLE `pcs_checkingout` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `style_id` int(11) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `scanned_pcs` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pcs_component_process`
--

CREATE TABLE `pcs_component_process` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `style_id` int(11) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `scanned_pcs` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pcs_sewingout`
--

CREATE TABLE `pcs_sewingout` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `style_id` int(11) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `scanned_pcs` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `podc_cancel`
--

CREATE TABLE `podc_cancel` (
  `id` int(50) NOT NULL,
  `podc_entry` varchar(255) NOT NULL,
  `podc_date` date NOT NULL,
  `opdc_canceltype` varchar(255) NOT NULL,
  `podc_entryno` varchar(255) NOT NULL,
  `opdc_cancelto` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `podc_cancel_det`
--

CREATE TABLE `podc_cancel_det` (
  `id` int(11) NOT NULL,
  `podc_cancel_id` int(11) DEFAULT NULL COMMENT 'podc_cancel',
  `cancel_type` varchar(25) DEFAULT NULL,
  `cancel_from` varchar(255) DEFAULT NULL,
  `cancel_id` int(11) DEFAULT NULL,
  `cancel_qty` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `process`
--

CREATE TABLE `process` (
  `id` int(11) NOT NULL,
  `department` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `process_name` varchar(255) DEFAULT NULL,
  `process_code` varchar(255) DEFAULT NULL,
  `process_price` float(10,2) DEFAULT NULL,
  `qc_approval` varchar(25) DEFAULT NULL,
  `process_type` varchar(255) DEFAULT NULL,
  `process_type_name` varchar(255) DEFAULT NULL,
  `budget_type` varchar(255) DEFAULT NULL,
  `can_edit` varchar(25) NOT NULL DEFAULT 'yes',
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `processing_list`
--

CREATE TABLE `processing_list` (
  `id` int(11) NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `scanning_type` varchar(25) DEFAULT NULL,
  `scanning_using` varchar(255) DEFAULT NULL,
  `scanning_for` varchar(25) DEFAULT NULL,
  `processing_code` varchar(255) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `input_type` varchar(25) DEFAULT NULL,
  `assigned_emp` int(11) DEFAULT NULL,
  `p_type` varchar(255) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `rework_stage` int(11) DEFAULT NULL,
  `production_unit` int(11) DEFAULT NULL,
  `boundle_id` longtext DEFAULT NULL,
  `bundle_track` longtext DEFAULT NULL COMMENT 'order_id->style->part->color->bundle_id->bundle_no',
  `piece_scanned` longtext DEFAULT NULL,
  `is_inhouse` varchar(255) DEFAULT NULL,
  `complete_inhouse` varchar(25) DEFAULT NULL,
  `is_inwarded` int(11) DEFAULT NULL COMMENT '1 => inwarded',
  `dc_num` varchar(255) DEFAULT NULL,
  `dc_date` date DEFAULT NULL,
  `is_checked` int(11) DEFAULT NULL,
  `quality_approval` int(11) DEFAULT NULL COMMENT '1 => approve, 2 => reject, 0 => waiting',
  `qc_approval` varchar(25) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `process_planing`
--

CREATE TABLE `process_planing` (
  `id` int(11) NOT NULL,
  `so_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `style_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `process_id` int(11) DEFAULT NULL COMMENT 'process',
  `plan_type` varchar(25) DEFAULT NULL,
  `partial_type` varchar(25) DEFAULT NULL,
  `process_type` varchar(25) DEFAULT NULL,
  `processing_unit_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_unit`
--

CREATE TABLE `production_unit` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qc_production`
--

CREATE TABLE `qc_production` (
  `id` int(11) NOT NULL,
  `prodessing_list_id` int(11) DEFAULT NULL,
  `ref_num` varchar(25) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `part` int(11) DEFAULT NULL,
  `color` int(11) DEFAULT NULL,
  `variation_value` int(11) DEFAULT NULL,
  `process_qty` int(11) DEFAULT NULL,
  `approved` int(11) DEFAULT NULL,
  `critical` int(11) DEFAULT NULL,
  `major` int(11) DEFAULT NULL,
  `minor` int(11) DEFAULT NULL,
  `defect` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order`
--

CREATE TABLE `sales_order` (
  `id` int(11) NOT NULL,
  `order_code` varchar(255) DEFAULT NULL,
  `order_qty` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `order_days` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `brand` int(11) DEFAULT NULL,
  `merchandiser` int(11) DEFAULT NULL,
  `merch_id` int(11) DEFAULT NULL,
  `currency` int(11) DEFAULT NULL,
  `season` varchar(255) DEFAULT NULL,
  `pack_type` varchar(255) DEFAULT NULL,
  `po_num` varchar(255) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `delivery_address` longtext DEFAULT NULL,
  `order_image` varchar(255) DEFAULT NULL,
  `is_approved` varchar(25) DEFAULT NULL,
  `budget_approve` varchar(25) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `is_dispatch` varchar(255) DEFAULT NULL,
  `dispatch_date` datetime DEFAULT NULL,
  `approvals` longtext DEFAULT NULL,
  `responsible_persons` longtext DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `edit_fab_requirement` varchar(25) NOT NULL DEFAULT 'yes',
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `fin_year` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_accessories_det`
--

CREATE TABLE `sales_order_accessories_det` (
  `id` int(11) NOT NULL,
  `program_id` int(11) DEFAULT NULL,
  `sod_size` int(11) DEFAULT NULL,
  `variation_value` int(11) DEFAULT NULL,
  `color` int(11) DEFAULT NULL,
  `det_req` int(11) DEFAULT NULL,
  `det_pcs` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_accessories_program`
--

CREATE TABLE `sales_order_accessories_program` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) DEFAULT NULL,
  `sales_order_detalis_id` int(11) DEFAULT NULL,
  `acc_type` int(11) DEFAULT NULL,
  `accessories` int(11) DEFAULT NULL,
  `acc_ref` varchar(255) DEFAULT NULL,
  `part` varchar(255) DEFAULT NULL,
  `size_wise` varchar(25) DEFAULT NULL,
  `color_wise` varchar(25) DEFAULT NULL,
  `excess` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_detalis`
--

CREATE TABLE `sales_order_detalis` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `style_no` varchar(255) DEFAULT NULL,
  `size_detail` longtext DEFAULT NULL,
  `part_detail` longtext DEFAULT NULL,
  `total_qty` varchar(255) DEFAULT NULL,
  `total_excess` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL COMMENT 'unit',
  `price` float(10,2) DEFAULT NULL,
  `total` float(10,2) DEFAULT NULL,
  `pack_type` varchar(255) DEFAULT NULL,
  `po_num` varchar(255) DEFAULT NULL,
  `color` int(11) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `main_fabric` int(11) DEFAULT NULL,
  `gsm` int(11) DEFAULT NULL,
  `style_des` varchar(255) DEFAULT NULL,
  `item_image` varchar(255) DEFAULT NULL,
  `excess` varchar(255) NOT NULL,
  `prod_bud_status` int(11) DEFAULT NULL COMMENT 'null=> ''Not Created'', 1=>''Not Reviewed'', 2=>''Partially Approved'', 3=>''Approved''	',
  `fabric_bud_status` int(11) DEFAULT NULL COMMENT 'null=> ''Not Created'', 1=>''Not Reviewed'', 2=>''Partially Approved'', 3=>''Approved''	',
  `access_bud_status` int(11) DEFAULT NULL COMMENT 'null=> ''Not Created'', 1=>''Not Reviewed'', 2=>''Partially Approved'', 3=>''Approved''	',
  `edit_fab_requirement` varchar(25) NOT NULL DEFAULT 'yes',
  `planing_type` varchar(25) DEFAULT NULL,
  `production_unit` int(11) DEFAULT NULL,
  `supplier` int(11) DEFAULT NULL,
  `billable_qty` int(11) DEFAULT NULL,
  `billable_qty_By` int(11) DEFAULT NULL,
  `billable_qty_unit` int(11) DEFAULT NULL,
  `billable_qty_date` datetime DEFAULT NULL,
  `billable_qty_approve` varchar(25) DEFAULT NULL,
  `create_subBill` varchar(25) DEFAULT NULL,
  `is_dispatch` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_fabric_components`
--

CREATE TABLE `sales_order_fabric_components` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `sales_order_detalis_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `sod_combo` int(11) DEFAULT NULL COMMENT 'sod_combo',
  `sod_part` int(11) DEFAULT NULL COMMENT 'sod_part',
  `sod_size` int(11) DEFAULT NULL COMMENT 'sod_size',
  `combo_id` int(11) DEFAULT NULL COMMENT 'color',
  `part_id` int(11) DEFAULT NULL COMMENT 'part',
  `part_color` int(11) DEFAULT NULL COMMENT 'color',
  `fabric_program_id` int(11) DEFAULT NULL COMMENT 'fabric_program',
  `fabric` int(11) DEFAULT NULL COMMENT 'fabric',
  `variation_value` int(11) DEFAULT NULL COMMENT 'variation_value',
  `order_qty` int(11) DEFAULT NULL,
  `excess` int(11) DEFAULT NULL,
  `excess_qty` int(11) DEFAULT NULL,
  `finishing_dia` int(11) DEFAULT NULL,
  `piece_wt` varchar(255) DEFAULT NULL,
  `req_wt` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_fabric_components_process`
--

CREATE TABLE `sales_order_fabric_components_process` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) DEFAULT NULL,
  `sales_order_detalis_id` int(11) DEFAULT NULL,
  `sod_combo` int(11) DEFAULT NULL,
  `sod_part` int(11) DEFAULT NULL,
  `combo_id` int(11) DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL,
  `part_color` int(11) DEFAULT NULL,
  `fabric_program_id` int(11) DEFAULT NULL,
  `fabric_component_id` int(11) DEFAULT NULL,
  `component` int(11) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `process_order` int(11) DEFAULT NULL,
  `lossPer` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_fabric_components_yarn`
--

CREATE TABLE `sales_order_fabric_components_yarn` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `sales_order_detalis_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `sod_combo` int(11) DEFAULT NULL COMMENT 'sod_combo',
  `sod_part` int(11) DEFAULT NULL COMMENT 'sod_part',
  `combo_id` int(11) DEFAULT NULL COMMENT 'color',
  `part_id` int(11) DEFAULT NULL COMMENT 'part',
  `part_color` int(11) DEFAULT NULL COMMENT 'color',
  `fabric_program_id` int(11) DEFAULT NULL COMMENT 'fabric_program',
  `fabric` int(11) DEFAULT NULL COMMENT 'fabric',
  `fabric_component_id` int(11) DEFAULT NULL,
  `component` int(11) DEFAULT NULL,
  `yarn_id` int(11) DEFAULT NULL COMMENT 'mas_yarn',
  `yarn_color` int(11) DEFAULT NULL COMMENT 'color',
  `mixed` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_fabric_program`
--

CREATE TABLE `sales_order_fabric_program` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `sales_order_detalis_id` int(11) DEFAULT NULL COMMENT 'sales_order_detalis',
  `sod_combo` int(11) DEFAULT NULL COMMENT 'sod_combo',
  `sod_part` int(11) DEFAULT NULL COMMENT 'sod_part',
  `combo_id` int(11) DEFAULT NULL COMMENT 'color',
  `part_id` int(11) DEFAULT NULL COMMENT 'part',
  `part_color` int(11) DEFAULT NULL COMMENT 'color',
  `fabric_type` varchar(255) DEFAULT NULL,
  `fabric` int(11) DEFAULT NULL COMMENT 'fabric',
  `gsm` varchar(255) DEFAULT NULL,
  `dyeing_color` int(11) DEFAULT NULL COMMENT 'color',
  `aop` varchar(255) DEFAULT NULL,
  `aop_name` varchar(255) DEFAULT NULL,
  `aop_image` varchar(255) DEFAULT NULL,
  `component` varchar(255) DEFAULT NULL,
  `component_detail` longtext DEFAULT NULL,
  `yarn_detail` longtext DEFAULT NULL,
  `tot_finishingDia` int(11) DEFAULT NULL,
  `tot_pieceWt` float(10,2) DEFAULT NULL,
  `tot_reqWt` float(10,2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_tasks`
--

CREATE TABLE `sales_order_tasks` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `task_type` varchar(255) DEFAULT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `resp_A` int(11) DEFAULT NULL,
  `resp_B` int(11) DEFAULT NULL,
  `resp_C` int(11) DEFAULT NULL,
  `resp_D` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selection_type`
--

CREATE TABLE `selection_type` (
  `id` int(11) NOT NULL,
  `type_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `ref` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sewing_input`
--

CREATE TABLE `sewing_input` (
  `id` int(11) NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `scanning_type` varchar(25) DEFAULT NULL,
  `scanning_using` varchar(255) DEFAULT NULL,
  `scanning_for` varchar(25) DEFAULT NULL,
  `processing_code` varchar(255) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `input_type` varchar(25) DEFAULT NULL,
  `assigned_emp` int(11) DEFAULT NULL,
  `p_type` varchar(255) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `rework_stage` int(11) DEFAULT NULL,
  `production_unit` int(11) DEFAULT NULL,
  `boundle_id` longtext DEFAULT NULL,
  `bundle_track` longtext DEFAULT NULL COMMENT 'order_id->style->part->color->bundle_id->bundle_no',
  `piece_scanned` longtext DEFAULT NULL,
  `is_inhouse` varchar(255) DEFAULT NULL,
  `complete_inhouse` varchar(25) DEFAULT NULL,
  `is_inwarded` int(11) DEFAULT NULL COMMENT '1 => inwarded',
  `dc_num` varchar(255) DEFAULT NULL,
  `dc_date` date DEFAULT NULL,
  `is_checked` int(11) DEFAULT NULL,
  `quality_approval` int(11) DEFAULT NULL COMMENT '1 => approve, 2 => reject, 0 => waiting',
  `qc_approval` varchar(25) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sewing_output`
--

CREATE TABLE `sewing_output` (
  `id` int(11) NOT NULL,
  `entry_num` varchar(25) DEFAULT NULL,
  `piece_id` longtext DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `size_details`
--

CREATE TABLE `size_details` (
  `id` int(11) NOT NULL,
  `so_id` varchar(255) DEFAULT NULL,
  `size_detail` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `size_details_edit`
--

CREATE TABLE `size_details_edit` (
  `id` int(11) NOT NULL,
  `itemlist_id` int(11) DEFAULT NULL,
  `size_detail` longtext DEFAULT NULL,
  `pack_detail` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sod_combo`
--

CREATE TABLE `sod_combo` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `sales_order_detail_id` int(11) DEFAULT NULL COMMENT 'sales_order_detail',
  `combo_id` int(11) DEFAULT NULL COMMENT 'color',
  `pack_id` int(11) DEFAULT NULL COMMENT 'mas_pack'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sod_part`
--

CREATE TABLE `sod_part` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `sales_order_detail_id` int(11) DEFAULT NULL COMMENT 'sales_order_detail',
  `sod_combo` int(11) DEFAULT NULL COMMENT 'sod_combo',
  `combo_id` int(11) DEFAULT NULL COMMENT 'color',
  `pack_id` int(11) DEFAULT NULL COMMENT 'mas_pack',
  `part_id` int(11) DEFAULT NULL COMMENT 'part',
  `color_id` int(11) DEFAULT NULL COMMENT 'color'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sod_size`
--

CREATE TABLE `sod_size` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) DEFAULT NULL COMMENT 'sales_order',
  `sales_order_detail_id` int(11) DEFAULT NULL COMMENT 'sales_order_detail',
  `sod_combo` int(11) DEFAULT NULL COMMENT 'sod_combo',
  `combo_id` int(11) DEFAULT NULL COMMENT 'color',
  `pack_id` int(11) DEFAULT NULL COMMENT 'mas_pack',
  `variation_value` int(11) DEFAULT NULL COMMENT 'variation_value',
  `size_qty` int(11) DEFAULT NULL,
  `excess_per` int(11) DEFAULT NULL,
  `excess_qty` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sod_time_sheet`
--

CREATE TABLE `sod_time_sheet` (
  `id` int(11) NOT NULL,
  `sales_order_id` int(11) DEFAULT NULL,
  `time_management_template_det` int(11) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `daily_time` int(11) DEFAULT NULL,
  `endday_time` int(11) DEFAULT NULL,
  `resp_a` longtext DEFAULT NULL,
  `resp_b` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `so_items`
--

CREATE TABLE `so_items` (
  `id` int(11) NOT NULL,
  `so_id` varchar(255) DEFAULT NULL,
  `cus_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `style_id` int(11) DEFAULT NULL,
  `size_detail` longtext DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_rate_history`
--

CREATE TABLE `sp_rate_history` (
  `id` int(11) NOT NULL,
  `sub_process` int(11) DEFAULT NULL,
  `old_price` float(10,2) DEFAULT NULL,
  `new_price` float(10,2) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `state_name` varchar(30) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stockgroup`
--

CREATE TABLE `stockgroup` (
  `id` int(11) NOT NULL,
  `groupname` varchar(255) NOT NULL,
  `assigneduser` varchar(255) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `style`
--

CREATE TABLE `style` (
  `id` int(11) NOT NULL,
  `style_name` varchar(255) DEFAULT NULL,
  `style_code` varchar(255) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_process`
--

CREATE TABLE `sub_process` (
  `id` int(11) NOT NULL,
  `department` int(11) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `sub_process_name` varchar(255) DEFAULT NULL,
  `sub_process_code` varchar(255) DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `supplier_code` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `phone1` varchar(255) DEFAULT NULL,
  `phone2` varchar(255) DEFAULT NULL,
  `emailid` varchar(255) DEFAULT NULL,
  `gst_no` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `wedd_date` date DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_main`
--

CREATE TABLE `tax_main` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_sub`
--

CREATE TABLE `tax_sub` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `tax_main` int(11) DEFAULT NULL,
  `percentage` varchar(25) DEFAULT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_date` date NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_tasks`
--

CREATE TABLE `team_tasks` (
  `id` int(11) NOT NULL,
  `task_type` varchar(255) DEFAULT NULL,
  `task_msg` varchar(255) DEFAULT NULL,
  `assigned_to` varchar(255) DEFAULT NULL,
  `assigned_toB` varchar(255) DEFAULT NULL,
  `task_duration` int(11) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `allowed_time` int(11) DEFAULT NULL,
  `task_complete` varchar(25) DEFAULT NULL,
  `task_proof` varchar(255) DEFAULT NULL,
  `completed_by` int(11) DEFAULT NULL,
  `completed_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_tasks_for`
--

CREATE TABLE `team_tasks_for` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `task_status` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_task_timer`
--

CREATE TABLE `team_task_timer` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `total_time` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timeline_history`
--

CREATE TABLE `timeline_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action_name` varchar(255) DEFAULT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `primary_id` int(11) DEFAULT NULL,
  `comment` longtext DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_management_template`
--

CREATE TABLE `time_management_template` (
  `id` int(11) NOT NULL,
  `temp_name` varchar(255) DEFAULT NULL,
  `total_day` int(11) DEFAULT NULL,
  `brand` varchar(500) DEFAULT NULL,
  `calculation_type` varchar(25) DEFAULT NULL,
  `start_day` int(11) DEFAULT NULL,
  `end_day` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_management_template_det`
--

CREATE TABLE `time_management_template_det` (
  `id` int(11) NOT NULL,
  `temp_id` int(11) DEFAULT NULL,
  `total_day` int(11) DEFAULT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `calculation_type` varchar(25) DEFAULT NULL,
  `start_day` int(11) DEFAULT NULL,
  `end_day` int(11) DEFAULT NULL,
  `res_dept` int(11) DEFAULT NULL,
  `daily_time` int(11) DEFAULT NULL,
  `endday_time` int(11) DEFAULT NULL,
  `resp_A` varchar(255) DEFAULT NULL,
  `resp_B` varchar(255) DEFAULT NULL,
  `resp_C` varchar(255) DEFAULT NULL,
  `resp_D` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `part_count` int(11) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `id` int(11) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_unit` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` int(11) NOT NULL,
  `user_group` int(11) DEFAULT NULL,
  `permission_name` varchar(255) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `created_user` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variation`
--

CREATE TABLE `variation` (
  `id` int(11) NOT NULL,
  `variation_name` varchar(255) DEFAULT NULL,
  `style_id` varchar(255) DEFAULT NULL,
  `is_active` varchar(55) DEFAULT 'active',
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variation_value`
--

CREATE TABLE `variation_value` (
  `id` int(11) NOT NULL,
  `variation_id` int(11) DEFAULT NULL,
  `style_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `bill_passing`
--
ALTER TABLE `bill_passing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_id` (`bill_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `created_unit` (`created_unit`);

--
-- Indexes for table `bill_passing_det`
--
ALTER TABLE `bill_passing_det`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_passing_id` (`bill_passing_id`),
  ADD KEY `cost_generation_det` (`cost_generation_det`);

--
-- Indexes for table `bill_receipt`
--
ALTER TABLE `bill_receipt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cost_id` (`cost_id`),
  ADD KEY `supplier` (`supplier`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `created_unit` (`created_unit`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `created_unit` (`created_unit`);

--
-- Indexes for table `budget_process`
--
ALTER TABLE `budget_process`
  ADD PRIMARY KEY (`id`),
  ADD KEY `so_id` (`so_id`),
  ADD KEY `style_id` (`style_id`),
  ADD KEY `department` (`department`),
  ADD KEY `category` (`category`),
  ADD KEY `process` (`process`),
  ADD KEY `yarn_id` (`yarn_id`),
  ADD KEY `fabric` (`fabric`),
  ADD KEY `dyeing_color` (`dyeing_color`),
  ADD KEY `accessories` (`accessories`),
  ADD KEY `requirement_id` (`requirement_id`);

--
-- Indexes for table `budget_process_partial`
--
ALTER TABLE `budget_process_partial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budget_process` (`budget_process`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `style_id` (`style_id`),
  ADD KEY `sod_combo` (`sod_combo`),
  ADD KEY `process` (`process`),
  ADD KEY `sod_part` (`sod_part`);

--
-- Indexes for table `budget_subprocess`
--
ALTER TABLE `budget_subprocess`
  ADD PRIMARY KEY (`id`),
  ADD KEY `so_id` (`so_id`),
  ADD KEY `style_id` (`style_id`),
  ADD KEY `department` (`department`),
  ADD KEY `category` (`category`),
  ADD KEY `process` (`process`),
  ADD KEY `subprocess` (`subprocess`);

--
-- Indexes for table `bundle_details`
--
ALTER TABLE `bundle_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cutting_barcode_id` (`cutting_barcode_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `style_id` (`style_id`),
  ADD KEY `sod_combo` (`sod_combo`),
  ADD KEY `sod_part` (`sod_part`),
  ADD KEY `sod_size` (`sod_size`),
  ADD KEY `combo` (`combo`),
  ADD KEY `part` (`part`),
  ADD KEY `color` (`color`),
  ADD KEY `variation_value` (`variation_value`),
  ADD KEY `in_proseccing_id` (`in_proseccing_id`),
  ADD KEY `in_sewing_id` (`in_sewing_id`),
  ADD KEY `created_unit` (`created_unit`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `bundle_piece_details`
--
ALTER TABLE `bundle_piece_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `checking_list`
--
ALTER TABLE `checking_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `checking_output`
--
ALTER TABLE `checking_output`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `common_comments`
--
ALTER TABLE `common_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `constants`
--
ALTER TABLE `constants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cost_generation`
--
ALTER TABLE `cost_generation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cost_generation_det`
--
ALTER TABLE `cost_generation_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cutting_barcode`
--
ALTER TABLE `cutting_barcode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cutting_partial_planning`
--
ALTER TABLE `cutting_partial_planning`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_detail`
--
ALTER TABLE `employee_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_detail_temp`
--
ALTER TABLE `employee_detail_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_main`
--
ALTER TABLE `expense_main`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_sub`
--
ALTER TABLE `expense_sub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabprogram_print`
--
ALTER TABLE `fabprogram_print`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabprogram_print_yarn`
--
ALTER TABLE `fabprogram_print_yarn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric`
--
ALTER TABLE `fabric`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_consumption`
--
ALTER TABLE `fabric_consumption`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_dc`
--
ALTER TABLE `fabric_dc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_dc_det`
--
ALTER TABLE `fabric_dc_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_dc_inw`
--
ALTER TABLE `fabric_dc_inw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_dc_inw_det`
--
ALTER TABLE `fabric_dc_inw_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_delivery`
--
ALTER TABLE `fabric_delivery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_delivery_det`
--
ALTER TABLE `fabric_delivery_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_opening`
--
ALTER TABLE `fabric_opening`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_opening_det`
--
ALTER TABLE `fabric_opening_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_po`
--
ALTER TABLE `fabric_po`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_po_det`
--
ALTER TABLE `fabric_po_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_po_expense`
--
ALTER TABLE `fabric_po_expense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_po_receipt`
--
ALTER TABLE `fabric_po_receipt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_po_receipt_det`
--
ALTER TABLE `fabric_po_receipt_det`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fabric_po_receipt` (`fabric_po_receipt`),
  ADD KEY `fabric_po_det` (`fabric_po_det`),
  ADD KEY `fabric_requirements` (`fabric_requirements`),
  ADD KEY `po_stage` (`po_stage`);

--
-- Indexes for table `fabric_requirements`
--
ALTER TABLE `fabric_requirements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabric_stock`
--
ALTER TABLE `fabric_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inhouse_daily_status`
--
ALTER TABLE `inhouse_daily_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inhouse_process`
--
ALTER TABLE `inhouse_process`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inwarded_bundle`
--
ALTER TABLE `inwarded_bundle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ironing`
--
ALTER TABLE `ironing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ironing_detail`
--
ALTER TABLE `ironing_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `itemlist`
--
ALTER TABLE `itemlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `line_planning`
--
ALTER TABLE `line_planning`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `line_planning_size`
--
ALTER TABLE `line_planning_size`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machine`
--
ALTER TABLE `machine`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_country`
--
ALTER TABLE `master_country`
  ADD PRIMARY KEY (`auto_number`);

--
-- Indexes for table `mas_accessories`
--
ALTER TABLE `mas_accessories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_accessories_type`
--
ALTER TABLE `mas_accessories_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_approval`
--
ALTER TABLE `mas_approval`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_bank`
--
ALTER TABLE `mas_bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_checking`
--
ALTER TABLE `mas_checking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_component`
--
ALTER TABLE `mas_component`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_currency`
--
ALTER TABLE `mas_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_defect`
--
ALTER TABLE `mas_defect`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_designation`
--
ALTER TABLE `mas_designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_line`
--
ALTER TABLE `mas_line`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_notes`
--
ALTER TABLE `mas_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_pack`
--
ALTER TABLE `mas_pack`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_stockitem`
--
ALTER TABLE `mas_stockitem`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_task`
--
ALTER TABLE `mas_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_uom`
--
ALTER TABLE `mas_uom`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mas_yarn`
--
ALTER TABLE `mas_yarn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchand_detail`
--
ALTER TABLE `merchand_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orbidx_checking`
--
ALTER TABLE `orbidx_checking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orbidx_component_process`
--
ALTER TABLE `orbidx_component_process`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orbidx_device`
--
ALTER TABLE `orbidx_device`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orbidx_garment_process`
--
ALTER TABLE `orbidx_garment_process`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orbidx_sewingout`
--
ALTER TABLE `orbidx_sewingout`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_process`
--
ALTER TABLE `order_process`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_tasks`
--
ALTER TABLE `order_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_order_id` (`sales_order_id`),
  ADD KEY `time_management_template_det` (`time_management_template_det`),
  ADD KEY `task_for` (`task_for`);

--
-- Indexes for table `order_task_timer`
--
ALTER TABLE `order_task_timer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packing`
--
ALTER TABLE `packing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packing_detail`
--
ALTER TABLE `packing_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `part`
--
ALTER TABLE `part`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments_for_bill_receipts`
--
ALTER TABLE `payments_for_bill_receipts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pcs_checkingout`
--
ALTER TABLE `pcs_checkingout`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pcs_component_process`
--
ALTER TABLE `pcs_component_process`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pcs_sewingout`
--
ALTER TABLE `pcs_sewingout`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `podc_cancel`
--
ALTER TABLE `podc_cancel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `podc_cancel_det`
--
ALTER TABLE `podc_cancel_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `process`
--
ALTER TABLE `process`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `processing_list`
--
ALTER TABLE `processing_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `process_planing`
--
ALTER TABLE `process_planing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_unit`
--
ALTER TABLE `production_unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qc_production`
--
ALTER TABLE `qc_production`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order`
--
ALTER TABLE `sales_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_accessories_det`
--
ALTER TABLE `sales_order_accessories_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_accessories_program`
--
ALTER TABLE `sales_order_accessories_program`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_detalis`
--
ALTER TABLE `sales_order_detalis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_fabric_components`
--
ALTER TABLE `sales_order_fabric_components`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_fabric_components_process`
--
ALTER TABLE `sales_order_fabric_components_process`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_fabric_components_yarn`
--
ALTER TABLE `sales_order_fabric_components_yarn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_fabric_program`
--
ALTER TABLE `sales_order_fabric_program`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_tasks`
--
ALTER TABLE `sales_order_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `selection_type`
--
ALTER TABLE `selection_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sewing_input`
--
ALTER TABLE `sewing_input`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sewing_output`
--
ALTER TABLE `sewing_output`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `size_details`
--
ALTER TABLE `size_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `size_details_edit`
--
ALTER TABLE `size_details_edit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sod_combo`
--
ALTER TABLE `sod_combo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_order_id` (`sales_order_id`),
  ADD KEY `sales_order_detail_id` (`sales_order_detail_id`),
  ADD KEY `combo_id` (`combo_id`),
  ADD KEY `pack_id` (`pack_id`);

--
-- Indexes for table `sod_part`
--
ALTER TABLE `sod_part`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sod_size`
--
ALTER TABLE `sod_size`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sod_time_sheet`
--
ALTER TABLE `sod_time_sheet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_order` (`sales_order_id`),
  ADD KEY `time_management_template_det` (`time_management_template_det`),
  ADD KEY `employee_detail` (`created_by`),
  ADD KEY `company` (`created_unit`);

--
-- Indexes for table `so_items`
--
ALTER TABLE `so_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sp_rate_history`
--
ALTER TABLE `sp_rate_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stockgroup`
--
ALTER TABLE `stockgroup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `style`
--
ALTER TABLE `style`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_process`
--
ALTER TABLE `sub_process`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_main`
--
ALTER TABLE `tax_main`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_sub`
--
ALTER TABLE `tax_sub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_tasks`
--
ALTER TABLE `team_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_tasks_for`
--
ALTER TABLE `team_tasks_for`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `team_task_timer`
--
ALTER TABLE `team_task_timer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `timeline_history`
--
ALTER TABLE `timeline_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_management_template`
--
ALTER TABLE `time_management_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_management_template_det`
--
ALTER TABLE `time_management_template_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variation`
--
ALTER TABLE `variation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variation_value`
--
ALTER TABLE `variation_value`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_passing`
--
ALTER TABLE `bill_passing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_passing_det`
--
ALTER TABLE `bill_passing_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_receipt`
--
ALTER TABLE `bill_receipt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget_process`
--
ALTER TABLE `budget_process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget_process_partial`
--
ALTER TABLE `budget_process_partial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget_subprocess`
--
ALTER TABLE `budget_subprocess`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bundle_details`
--
ALTER TABLE `bundle_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bundle_piece_details`
--
ALTER TABLE `bundle_piece_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checking_list`
--
ALTER TABLE `checking_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checking_output`
--
ALTER TABLE `checking_output`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `color`
--
ALTER TABLE `color`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `common_comments`
--
ALTER TABLE `common_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `constants`
--
ALTER TABLE `constants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cost_generation`
--
ALTER TABLE `cost_generation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cost_generation_det`
--
ALTER TABLE `cost_generation_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cutting_barcode`
--
ALTER TABLE `cutting_barcode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cutting_partial_planning`
--
ALTER TABLE `cutting_partial_planning`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_detail`
--
ALTER TABLE `employee_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_detail_temp`
--
ALTER TABLE `employee_detail_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_main`
--
ALTER TABLE `expense_main`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_sub`
--
ALTER TABLE `expense_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabprogram_print`
--
ALTER TABLE `fabprogram_print`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabprogram_print_yarn`
--
ALTER TABLE `fabprogram_print_yarn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric`
--
ALTER TABLE `fabric`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_consumption`
--
ALTER TABLE `fabric_consumption`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_dc`
--
ALTER TABLE `fabric_dc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_dc_det`
--
ALTER TABLE `fabric_dc_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_dc_inw`
--
ALTER TABLE `fabric_dc_inw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_dc_inw_det`
--
ALTER TABLE `fabric_dc_inw_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_delivery`
--
ALTER TABLE `fabric_delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_delivery_det`
--
ALTER TABLE `fabric_delivery_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_opening`
--
ALTER TABLE `fabric_opening`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_opening_det`
--
ALTER TABLE `fabric_opening_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_po`
--
ALTER TABLE `fabric_po`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_po_det`
--
ALTER TABLE `fabric_po_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_po_expense`
--
ALTER TABLE `fabric_po_expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_po_receipt`
--
ALTER TABLE `fabric_po_receipt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_po_receipt_det`
--
ALTER TABLE `fabric_po_receipt_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_requirements`
--
ALTER TABLE `fabric_requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabric_stock`
--
ALTER TABLE `fabric_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inhouse_daily_status`
--
ALTER TABLE `inhouse_daily_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inhouse_process`
--
ALTER TABLE `inhouse_process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inwarded_bundle`
--
ALTER TABLE `inwarded_bundle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ironing`
--
ALTER TABLE `ironing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ironing_detail`
--
ALTER TABLE `ironing_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `itemlist`
--
ALTER TABLE `itemlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `line_planning`
--
ALTER TABLE `line_planning`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `line_planning_size`
--
ALTER TABLE `line_planning_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine`
--
ALTER TABLE `machine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_country`
--
ALTER TABLE `master_country`
  MODIFY `auto_number` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_accessories`
--
ALTER TABLE `mas_accessories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_accessories_type`
--
ALTER TABLE `mas_accessories_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_approval`
--
ALTER TABLE `mas_approval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_bank`
--
ALTER TABLE `mas_bank`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_checking`
--
ALTER TABLE `mas_checking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_component`
--
ALTER TABLE `mas_component`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_currency`
--
ALTER TABLE `mas_currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_defect`
--
ALTER TABLE `mas_defect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_designation`
--
ALTER TABLE `mas_designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_line`
--
ALTER TABLE `mas_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_notes`
--
ALTER TABLE `mas_notes`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_pack`
--
ALTER TABLE `mas_pack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_stockitem`
--
ALTER TABLE `mas_stockitem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_task`
--
ALTER TABLE `mas_task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_uom`
--
ALTER TABLE `mas_uom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_yarn`
--
ALTER TABLE `mas_yarn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `merchand_detail`
--
ALTER TABLE `merchand_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orbidx_checking`
--
ALTER TABLE `orbidx_checking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orbidx_component_process`
--
ALTER TABLE `orbidx_component_process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orbidx_device`
--
ALTER TABLE `orbidx_device`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orbidx_garment_process`
--
ALTER TABLE `orbidx_garment_process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orbidx_sewingout`
--
ALTER TABLE `orbidx_sewingout`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_process`
--
ALTER TABLE `order_process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_tasks`
--
ALTER TABLE `order_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_task_timer`
--
ALTER TABLE `order_task_timer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packing`
--
ALTER TABLE `packing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packing_detail`
--
ALTER TABLE `packing_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `part`
--
ALTER TABLE `part`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments_for_bill_receipts`
--
ALTER TABLE `payments_for_bill_receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcs_checkingout`
--
ALTER TABLE `pcs_checkingout`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcs_component_process`
--
ALTER TABLE `pcs_component_process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcs_sewingout`
--
ALTER TABLE `pcs_sewingout`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `podc_cancel`
--
ALTER TABLE `podc_cancel`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `podc_cancel_det`
--
ALTER TABLE `podc_cancel_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `process`
--
ALTER TABLE `process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `processing_list`
--
ALTER TABLE `processing_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `process_planing`
--
ALTER TABLE `process_planing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_unit`
--
ALTER TABLE `production_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qc_production`
--
ALTER TABLE `qc_production`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order`
--
ALTER TABLE `sales_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_accessories_det`
--
ALTER TABLE `sales_order_accessories_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_accessories_program`
--
ALTER TABLE `sales_order_accessories_program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_detalis`
--
ALTER TABLE `sales_order_detalis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_fabric_components`
--
ALTER TABLE `sales_order_fabric_components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_fabric_components_process`
--
ALTER TABLE `sales_order_fabric_components_process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_fabric_components_yarn`
--
ALTER TABLE `sales_order_fabric_components_yarn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_fabric_program`
--
ALTER TABLE `sales_order_fabric_program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_tasks`
--
ALTER TABLE `sales_order_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selection_type`
--
ALTER TABLE `selection_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sewing_input`
--
ALTER TABLE `sewing_input`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sewing_output`
--
ALTER TABLE `sewing_output`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `size_details_edit`
--
ALTER TABLE `size_details_edit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sod_combo`
--
ALTER TABLE `sod_combo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sod_part`
--
ALTER TABLE `sod_part`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sod_size`
--
ALTER TABLE `sod_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sod_time_sheet`
--
ALTER TABLE `sod_time_sheet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `so_items`
--
ALTER TABLE `so_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_rate_history`
--
ALTER TABLE `sp_rate_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stockgroup`
--
ALTER TABLE `stockgroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `style`
--
ALTER TABLE `style`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_process`
--
ALTER TABLE `sub_process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_main`
--
ALTER TABLE `tax_main`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_sub`
--
ALTER TABLE `tax_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_tasks`
--
ALTER TABLE `team_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_tasks_for`
--
ALTER TABLE `team_tasks_for`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_task_timer`
--
ALTER TABLE `team_task_timer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timeline_history`
--
ALTER TABLE `timeline_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_management_template`
--
ALTER TABLE `time_management_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_management_template_det`
--
ALTER TABLE `time_management_template_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variation`
--
ALTER TABLE `variation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variation_value`
--
ALTER TABLE `variation_value`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
