<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_finance_report extends CI_Model{

    function get_fixed_asset_from_nav(){
        $db = $this->load->database('default2', true);
        $query_temp = "SELECT * FROM fixed_asset;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_ar_amount_by_invoice_date($year, $month){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select rangee, rangee_order, sum(amount) as amount
            from (
            select cust_no, invc_no, amount, [Posting Date], [Due Date], [Closed at Date], term_day, payment_day, late_day,

            case
            	when payment_day between 0 and 15 then '0-15'
            	when payment_day between 16 and 30 then '16-30'
            	when payment_day between 31 and 45 then '31-45'
            	when payment_day between 46 and 60 then '46-60'
            	when payment_day between 61 and 90 then '61-90'
            	when payment_day between 91 and 120 then '91-120'
            	when payment_day > 120 then '>120'
            	when payment_day = '-9999' then '-9999'
            end as rangee,

            case
            	when payment_day between 0 and 15 then 'A'
            	when payment_day between 16 and 30 then 'B'
            	when payment_day between 31 and 45 then 'C'
            	when payment_day between 46 and 60 then 'D'
            	when payment_day between 61 and 90 then 'E'
            	when payment_day between 91 and 120 then 'F'
            	when payment_day > 120 then 'G'
            	when payment_day = '-9999' then 'H'
            end as rangee_order

            from (
            select cust_no, invc_no, sum(amount) as amount
            from (
            select [Customer No_] as cust_no,[Document No_] as invc_no,'' as cn_no, [Sales (LCY)] as amount
            from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where ([Customer No_] like '1%' or [Customer No_] like '2%')
            and  [Document Type]='2' and year([Posting Date])='".$year."' and month([Posting Date])='".$month."'

            union

            select  tbl_cn.[Customer No_],tbl_cn2.[Document No_] as invc_no, tbl_cn.[Document No_] as cn_no, cn_amount as amount
            from (
            select [Customer No_],[Cust_ Ledger Entry No_], [Document No_], [Amount (LCY)] as cn_amount
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Document Type]='3' and [Entry Type]='2'
            and [Cust_ Ledger Entry No_] in (select distinct([Entry No_])
            from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where ([Customer No_] like '1%' or [Customer No_] like '2%')
            and  [Document Type]='2' and year([Posting Date])='".$year."' and month([Posting Date])='".$month."')) as tbl_cn

            left join(
            select [Entry No_],[Document No_]
            from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where [Entry No_] in (
            select distinct([Cust_ Ledger Entry No_])
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Document Type]='3' and [Entry Type]='2'
            and [Cust_ Ledger Entry No_] in (select distinct([Entry No_])
            from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where ([Customer No_] like '1%' or [Customer No_] like '2%')
            and  [Document Type]='2' and year([Posting Date])='".$year."' and month([Posting Date])='".$month."'))
            ) as tbl_cn2 on(tbl_cn.[Cust_ Ledger Entry No_]=tbl_cn2.[Entry No_])) as tbl_amount group by cust_no, invc_no) as tbl_invc

            left join(
            select [Customer No_], [Posting Date], [Document No_],[Due Date],  [Closed at Date],
            datediff(DAY,[Posting Date],[Due Date]) as term_day,

            case
            	when [Closed at Date]='1753-01-01' then '-9999'
            	else DATEDIFF(DAY, [Posting Date], [Closed at Date])
            end as payment_day,

            case
            	when [Closed at Date]='1753-01-01' then '-9999'
            	else DATEDIFF(DAY, [Posting Date], [Closed at Date]) - datediff(DAY,[Posting Date],[Due Date])
            end as late_day

            from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where ([Customer No_] like '1%' or [Customer No_] like '2%')
            and  [Document Type]='2' and year([Posting Date])='".$year."' and month([Posting Date])='".$month."') as tbl_due_date on(tbl_due_date.[Document No_]=tbl_invc.invc_no)) as tbl
            group by rangee, rangee_order order by rangee_order;";



        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_ar_credit_term_by_invoice_date($year, $month){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select grade,grade_text,count(grade) as grade_count
          from (
          select cust_no, avgg_lateday,
          case
          	when avgg_lateday between -1000 and 0  then 'A'
          	when avgg_lateday between 1 and 7 then 'B'
          	when avgg_lateday between 8 and 15 then 'C'
          	when avgg_lateday between 16 and 30 then 'D'
          	when avgg_lateday between 31 and 60 then 'E'
          	when avgg_lateday between 61 and 90 then 'F'
          	when avgg_lateday > 90 then 'G'
          	when avgg_lateday < -1000 then 'G'
          end as grade,

          case
          	when avgg_lateday between -1000 and 0  then 'on time'
          	when avgg_lateday between 1 and 7 then '1-7'
          	when avgg_lateday between 8 and 15 then '8-15'
          	when avgg_lateday between 16 and 30 then '16-30'
          	when avgg_lateday between 31 and 60 then '31-60'
          	when avgg_lateday between 61 and 90 then '61-90'
          	when avgg_lateday > 90 then '> 90'
          	when avgg_lateday < -1000 then '> 90'
          end as grade_text

          from (
          select cust_no, avg(late_day) as avgg_lateday
          from (
          select cust_no, invc_no, amount, [Posting Date], [Due Date], [Closed at Date], term_day, payment_day, late_day

          from (
          select cust_no, invc_no, sum(amount) as amount
          from (
          select [Customer No_] as cust_no,[Document No_] as invc_no,'' as cn_no, [Sales (LCY)] as amount
          from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where ([Customer No_] like '1%' or [Customer No_] like '2%')
          and  [Document Type]='2' and year([Posting Date])='".$year."' and month([Posting Date])='".$month."'

          union

          select  tbl_cn.[Customer No_],tbl_cn2.[Document No_] as invc_no, tbl_cn.[Document No_] as cn_no, cn_amount as amount
          from (
          select [Customer No_],[Cust_ Ledger Entry No_], [Document No_], [Amount (LCY)] as cn_amount
          from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Document Type]='3' and [Entry Type]='2'
          and [Cust_ Ledger Entry No_] in (select distinct([Entry No_])
          from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where ([Customer No_] like '1%' or [Customer No_] like '2%')
          and  [Document Type]='2' and year([Posting Date])='".$year."' and month([Posting Date])='".$month."')) as tbl_cn

          left join(
          select [Entry No_],[Document No_]
          from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where [Entry No_] in (
          select distinct([Cust_ Ledger Entry No_])
          from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Document Type]='3' and [Entry Type]='2'
          and [Cust_ Ledger Entry No_] in (select distinct([Entry No_])
          from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where ([Customer No_] like '1%' or [Customer No_] like '2%')
          and  [Document Type]='2' and year([Posting Date])='".$year."' and month([Posting Date])='".$month."'))
          ) as tbl_cn2 on(tbl_cn.[Cust_ Ledger Entry No_]=tbl_cn2.[Entry No_])) as tbl_amount group by cust_no, invc_no) as tbl_invc

          left join(
          select [Customer No_], [Posting Date], [Document No_],[Due Date],  [Closed at Date],
          datediff(DAY,[Posting Date],[Due Date]) as term_day,

          case
          	when [Closed at Date]='1753-01-01' then '-9999'
          	else DATEDIFF(DAY, [Posting Date], [Closed at Date])
          end as payment_day,

          case
          	when [Closed at Date]='1753-01-01' then '-9999'
          	else DATEDIFF(DAY, [Posting Date], [Closed at Date]) - datediff(DAY,[Posting Date],[Due Date])
          end as late_day

          from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where ([Customer No_] like '1%' or [Customer No_] like '2%')
          and  [Document Type]='2' and year([Posting Date])='".$year."' and month([Posting Date])='".$month."') as tbl_due_date on(tbl_due_date.[Document No_]=tbl_invc.invc_no)) as tbl
          group by cust_no) as tbl) as tbl group by grade, grade_text order by grade;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_ar_amount_by_payment_date($year, $month){
        $db = $this->load->database('sql_server_live', true);

        /*$query_temp = "select grade, sum(payment_amount)*-1 as payment_amount,
        	case
        		when grade='A' then '0-15'
        		when grade='B' then '16-30'
        		when grade='C' then '31-45'
        		when grade='D' then '46-60'
        		when grade='E' then '61-90'
        		when grade='F' then '91-120'
        		when grade='G' then '>120'
        	end grade_text
        from(
        select *,
        	case
        		when payment_day between 0 and 15 then 'A'
        		when payment_day between 16 and 30 then 'B'
        		when payment_day between 31 and 45 then 'C'
        		when payment_day between 46 and 60 then 'D'
        		when payment_day between 61 and 90 then 'E'
        		when payment_day between 91 and 120 then 'F'
        		when payment_day >120 then 'G'
        		when payment_day between -300 and -1 then 'A'
        	end as grade
        from (
        select *, datediff(day,invc_date,payment_date) as payment_day
        from (
        select tbl.[Entry No_],tbl.[Customer No_], tbl.[Posting Date] as payment_date, tbl.[Document No_] as payment_doc_no,
        tbl.[Closed at Date] as payment_close_date, tbl.[Closed by Amount (LCY)] as payment_close_amount,
        tbl.[Cust_ Ledger Entry No_], tbl.[Amount (LCY)] as payment_amount, tbl.[Applied Cust_ Ledger Entry No_],
        custleden.[Document No_] as invc_no, custleden.[Posting Date] as invc_date,

        case
        	when custleden.[Due Date]='1753-01-01 00:00:00.000' and custleden.[Closed at Date]!='1753-01-01 00:00:00.000' then custleden.[Closed at Date]
        	else custleden.[Due Date]
        end invc_due_date,

        custleden.[Closed at Date] as invc_close_date

        from (
        select  h.[Entry No_],h.[Customer No_], h.[Posting Date], h.[Document No_], [Closed at Date], [Closed by Amount (LCY)],[Cust_ Ledger Entry No_], [Amount (LCY)], [Applied Cust_ Ledger Entry No_]
        from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] h
        inner join [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] d on(h.[Entry No_]=d.[Applied Cust_ Ledger Entry No_])

        where h.[Document Type]='1' and year(h.[Posting Date])='".$year."' and month(h.[Posting Date])='".$month."' and [Amount (LCY)]<0) as tbl

        left join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] as custleden on(custleden.[Entry No_]=tbl.[Cust_ Ledger Entry No_])) as tbl) as tbl) as tbl group by grade;";*/

        /*$query_temp = "select grade, sum(amount) as payment_amount,
        	case
        		when grade='A' then '0-15'
        		when grade='B' then '16-30'
        		when grade='C' then '31-45'
        		when grade='D' then '46-60'
        		when grade='E' then '61-90'
        		when grade='F' then '91-120'
        		when grade='G' then '>120'
        	end grade_text
        from(
        select *,
        case
          when payment_day between 0 and 15 then 'A'
          when payment_day between 16 and 30 then 'B'
          when payment_day between 31 and 45 then 'C'
          when payment_day between 46 and 60 then 'D'
          when payment_day between 61 and 90 then 'E'
          when payment_day between 91 and 120 then 'F'
          when payment_day >120 then 'G'
          when payment_day between -300 and -1 then 'A'
        end as grade
        from (
        select *, datediff(day,invc_date,payment_date) as payment_day
        from (
        select [Entry No_] as entry_no, [Customer No_] as cust_no, tbl_payment.[Document No_] as payment_doc,tbl_payment.[Posting Date] as payment_date,
        amount, tbl_applied.[Document No_] as payment_doc2, invc_date, invc_due_date, invc_closed_date
        from (
        select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date], sum(tbl_d.[Amount (LCY)])*-1 as amount
        from (
        select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

        inner join (
        select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
        on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date]) as tbl_payment

        left join (
        select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
        from (
        select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
        from (
        select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
        from (
        select [Document No_],[Cust_ Ledger Entry No_]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
        where [Document No_] in (
        select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
        on(tbl_payment.[Document No_]=tbl_applied.[Document No_]) where tbl_applied.[Document No_] is not null

        union

        select [Entry No_] as entry_no,[Customer No_] as cust_no,tbl.[Document No_] as payment_doc,tbl.[Posting Date] as payment_date,
        tbl.amount as amount,tbl_a.payment_doc as payment_doc2, invc_date, invc_due_date, invc_closed_date
        from (
        select [Entry No_], [Customer No_],tbl_payment.[Document No_], tbl_payment.amount, tbl_payment.[Posting Date] from (
        select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date], sum(tbl_d.[Amount (LCY)])*-1 as amount
        from (
        select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

        inner join (
        select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
        on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date]) as tbl_payment

        left join (
        select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
        from (
        select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
        from (
        select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
        from (
        select [Document No_],[Cust_ Ledger Entry No_]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
        where [Document No_] in (
        select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
        on(tbl_payment.[Document No_]=tbl_applied.[Document No_]) where tbl_applied.[Document No_] is null) as tbl

        inner join (
        select tbl.[Cust_ Ledger Entry No_], tbl.invc_no, tbl.payment_doc, tbl_a.[Posting Date] as invc_date, tbl_a.[Due Date] as invc_due_date, tbl_a.[Closed at Date] as invc_closed_date
        from (
        select tbl.[Cust_ Ledger Entry No_], tbl.invc_no, tbl_a.[Document No_] as payment_doc
        from(
        select [Cust_ Ledger Entry No_], [Document No_] as invc_no
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in (
        select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where [Document No_] in (
        select distinct tbl_payment.[Document No_] from (
        select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_], sum(tbl_d.[Amount (LCY)])*-1 as amount
        from (
        select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

        inner join (
        select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
        on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_]) as tbl_payment

        left join (
        select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
        from (
        select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
        from (
        select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
        from (
        select [Document No_],[Cust_ Ledger Entry No_]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
        where [Document No_] in (
        select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
        on(tbl_payment.[Document No_]=tbl_applied.[Document No_])  where tbl_applied.[Document No_] is null)) and [Entry Type]='2' and [Document Type]='2') as tbl
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] tbl_a on(tbl.[Cust_ Ledger Entry No_]=tbl_a.[Entry No_])) as tbl
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] tbl_a on(tbl_a.[Document No_]=tbl.invc_no)) as tbl_a
        on(tbl.[Document No_]=tbl_a.payment_doc)) as tbl) as tbl) as tbl group by grade";
*/

          $query_temp = "select grade, sum(amount) as payment_amount,
        case
           when grade='A' then '0-15'
           when grade='B' then '16-30'
           when grade='C' then '31-45'
           when grade='D' then '46-60'
           when grade='E' then '61-90'
           when grade='F' then '91-120'
           when grade='G' then '>120'
           end grade_text
        from(
        select *,
        case
          when payment_day between 0 and 15 then 'A'
          when payment_day between 16 and 30 then 'B'
          when payment_day between 31 and 45 then 'C'
          when payment_day between 46 and 60 then 'D'
          when payment_day between 61 and 90 then 'E'
          when payment_day between 91 and 120 then 'F'
          when payment_day >120 then 'G'
          when payment_day between -300 and -1 then 'A'
        end as grade
        from (
        select *, datediff(day,invc_date,payment_date) as payment_day  from (
        select cust_no, payment_doc,payment_date,payment_doc2, invc_date, invc_due_date, invc_closed_date, sum(amount) as amount
        from (
        select * from (
        select [Entry No_] as entry_no, [Customer No_] as cust_no, tbl_payment.[Document No_] as payment_doc,tbl_payment.[Posting Date] as payment_date,
        amount, tbl_applied.[Document No_] as payment_doc2, invc_date, invc_due_date, invc_closed_date
        from (
        select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date], sum(tbl_d.[Amount (LCY)])*-1 as amount
        from (
        select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

        inner join (
        select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
        on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date]) as tbl_payment

        left join (
        select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
        from (
        select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
        from (
        select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
        from (
        select [Document No_],[Cust_ Ledger Entry No_]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
        where [Document No_] in (
        select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
        on(tbl_payment.[Document No_]=tbl_applied.[Document No_]) where tbl_applied.[Document No_] is not null

        union

        select [Entry No_] as entry_no,[Customer No_] as cust_no,tbl.[Document No_] as payment_doc,tbl.[Posting Date] as payment_date,
        tbl.amount as amount,tbl_a.payment_doc as payment_doc2, invc_date, invc_due_date, invc_closed_date
        from (
        select [Entry No_], [Customer No_],tbl_payment.[Document No_], tbl_payment.amount, tbl_payment.[Posting Date] from (
        select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date], sum(tbl_d.[Amount (LCY)])*-1 as amount
        from (
        select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

        inner join (
        select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
        on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date]) as tbl_payment

        left join (
        select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
        from (
        select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
        from (
        select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
        from (
        select [Document No_],[Cust_ Ledger Entry No_]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
        where [Document No_] in (
        select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
        on(tbl_payment.[Document No_]=tbl_applied.[Document No_]) where tbl_applied.[Document No_] is null) as tbl

        inner join (
        select tbl.[Cust_ Ledger Entry No_], tbl.invc_no, tbl.payment_doc, tbl_a.[Posting Date] as invc_date, tbl_a.[Due Date] as invc_due_date, tbl_a.[Closed at Date] as invc_closed_date
        from (
        select tbl.[Cust_ Ledger Entry No_], tbl.invc_no, tbl_a.[Document No_] as payment_doc
        from(
        select [Cust_ Ledger Entry No_], [Document No_] as invc_no
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in (
        select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where [Document No_] in (
        select distinct tbl_payment.[Document No_] from (
        select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_], sum(tbl_d.[Amount (LCY)])*-1 as amount
        from (
        select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

        inner join (
        select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
        on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_]) as tbl_payment

        left join (
        select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
        from (
        select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
        from (
        select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
        from (
        select [Document No_],[Cust_ Ledger Entry No_]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
        where [Document No_] in (
        select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
        on(tbl_payment.[Document No_]=tbl_applied.[Document No_])  where tbl_applied.[Document No_] is null)) and [Entry Type]='2' and [Document Type]='2') as tbl
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] tbl_a on(tbl.[Cust_ Ledger Entry No_]=tbl_a.[Entry No_])) as tbl
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] tbl_a on(tbl_a.[Document No_]=tbl.invc_no)) as tbl_a on(tbl.[Document No_]=tbl_a.payment_doc)) as tbl /* end */

        union

        select tbl.[Entry No_] as entry_no,tbl.[Customer No_] as cust_no,tbl.payment_doc, tbl.[Posting Date] as payment_date, detcustleden.[Amount (LCY)]*-1  as amount,
        tbl.payment_doc as payment_doc2, '' as invc_date,'' as invc_due_date,'' as invc_closed_date
        from (
        select tbl.[Entry No_],tbl.[Closed by Entry No_],custleden.[Document No_] as payment_doc, custleden.[Posting Date], custleden.[Customer No_]
        from (
        select [Entry No_], [Closed by Entry No_]
        from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where [Closed by Entry No_] in (select [Cust_ Ledger Entry No_]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%')
        and [Amount (LCY)]<0 and [Entry Type]='1') and [Document Type]='6') as tbl
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] as custleden on(custleden.[Entry No_]=tbl.[Closed by Entry No_])) as tbl
        inner join [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] as detcustleden on(detcustleden.[Cust_ Ledger Entry No_]=tbl.[Entry No_]) and [Document Type]='6'
        and
        tbl.[Closed by Entry No_] in (select [Entry No_]
        from (
        select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date], sum(tbl_d.[Amount (LCY)])*-1 as amount
        from (
        select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

        inner join (
        select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
        on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date]) as tbl_payment

        left join (
        select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
        from (
        select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
        from (
        select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
        from (
        select [Document No_],[Cust_ Ledger Entry No_]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
        where [Document No_] in (
        select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
        on(tbl_payment.[Document No_]=tbl_applied.[Document No_]) where tbl_applied.[Document No_] is not null

        union

        select [Entry No_]
        from (
        select [Entry No_], [Customer No_],tbl_payment.[Document No_], tbl_payment.amount, tbl_payment.[Posting Date] from (
        select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date], sum(tbl_d.[Amount (LCY)])*-1 as amount
        from (
        select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

        inner join (
        select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
        on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date]) as tbl_payment

        left join (
        select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
        from (
        select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
        from (
        select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
        from (
        select [Document No_],[Cust_ Ledger Entry No_]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
        where [Document No_] in (
        select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
        on(tbl_payment.[Document No_]=tbl_applied.[Document No_]) where tbl_applied.[Document No_] is null) as tbl

        inner join (
        select tbl.[Cust_ Ledger Entry No_], tbl.invc_no, tbl.payment_doc, tbl_a.[Posting Date] as invc_date, tbl_a.[Due Date] as invc_due_date, tbl_a.[Closed at Date] as invc_closed_date
        from (
        select tbl.[Cust_ Ledger Entry No_], tbl.invc_no, tbl_a.[Document No_] as payment_doc
        from(
        select [Cust_ Ledger Entry No_], [Document No_] as invc_no
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in (
        select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where [Document No_] in (
        select distinct tbl_payment.[Document No_] from (
        select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_], sum(tbl_d.[Amount (LCY)])*-1 as amount
        from (
        select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

        inner join (
        select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
        on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_]) as tbl_payment

        left join (
        select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
        from (
        select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
        from (
        select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
        from (
        select [Document No_],[Cust_ Ledger Entry No_]
        from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
        where [Document No_] in (
        select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
        where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
        on(tbl_payment.[Document No_]=tbl_applied.[Document No_])  where tbl_applied.[Document No_] is null)) and [Entry Type]='2' and [Document Type]='2') as tbl
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] tbl_a on(tbl.[Cust_ Ledger Entry No_]=tbl_a.[Entry No_])) as tbl
        inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] tbl_a on(tbl_a.[Document No_]=tbl.invc_no)) as tbl_a on(tbl.[Document No_]=tbl_a.payment_doc))) as tbl
        group by cust_no, payment_doc,payment_date,payment_doc2, invc_date, invc_due_date, invc_closed_date) as tbl) as tbl) as tbl group by grade;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_ar_credit_term_by_payment_date($year, $month){
        $db = $this->load->database('sql_server_live', true);
        /*$query_temp = "select grade, grade_text, count(grade) as grade_count,  sum(payment_amount)*-1 as payment_amount
          from (
          select *,
          case
             when pay_late between -1000 and 0  then 'A'
             when pay_late between 1 and 7 then 'B'
             when pay_late between 8 and 15 then 'C'
             when pay_late between 16 and 30 then 'D'
             when pay_late between 31 and 60 then 'E'
             when pay_late between 61 and 90 then 'F'
             when pay_late > 90 then 'G'
             when pay_late < -1000 then 'G'
          end as grade,

          case
             when pay_late between -1000 and 0  then 'on time'
             when pay_late between 1 and 7 then '1-7'
             when pay_late between 8 and 15 then '8-15'
             when pay_late between 16 and 30 then '16-30'
             when pay_late between 31 and 60 then '31-60'
             when pay_late between 61 and 90 then '61-90'
             when pay_late > 90 then '> 90'
             when pay_late < -1000 then '> 90'
          end as grade_text
          from (
          select cust_no, AVG(pay_late) as pay_late,  sum(payment_amount) as payment_amount
          from (
          select *, DATEDIFF(day,payment_date,invc_close_date) as pay_late
          from (
          select tbl.[Entry No_],tbl.[Customer No_] as cust_no, tbl.[Posting Date] as payment_date, tbl.[Document No_] as payment_doc_no,
          tbl.[Closed at Date] as payment_close_date, tbl.[Closed by Amount (LCY)] as payment_close_amount,
          tbl.[Cust_ Ledger Entry No_], tbl.[Amount (LCY)] as payment_amount, tbl.[Applied Cust_ Ledger Entry No_],
          custleden.[Document No_] as invc_no, custleden.[Posting Date] as invc_date,

          case
          	when custleden.[Due Date]='1753-01-01 00:00:00.000' and custleden.[Closed at Date]!='1753-01-01 00:00:00.000' then custleden.[Closed at Date]
          	else custleden.[Due Date]
          end invc_due_date,

          custleden.[Closed at Date] as invc_close_date

          from (
          select  h.[Entry No_],h.[Customer No_], h.[Posting Date], h.[Document No_], [Closed at Date], [Closed by Amount (LCY)],[Cust_ Ledger Entry No_], [Amount (LCY)], [Applied Cust_ Ledger Entry No_]
          from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] h
          inner join [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] d on(h.[Entry No_]=d.[Cust_ Ledger Entry No_])

          where h.[Document Type]='1' and year(h.[Posting Date])='".$year."' and month(h.[Posting Date])='".$month."' and [Amount (LCY)]<0) as tbl

          left join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] as custleden on(custleden.[Entry No_]=tbl.[Cust_ Ledger Entry No_])) as tbl) as tbl group by cust_no) as tbl) as tbl group by grade, grade_text order by grade;";
          */

        /*  $query_temp = "select grade, grade_text, count(grade) as grade_count,  sum(payment_amount) as payment_amount
          from (
          select *,
            case
                when pay_late between -1000 and 0  then 'A'
                when pay_late between 1 and 7 then 'B'
                when pay_late between 8 and 15 then 'C'
                when pay_late between 16 and 30 then 'D'
                when pay_late between 31 and 60 then 'E'
                when pay_late between 61 and 90 then 'F'
                when pay_late > 90 then 'G'
                when pay_late < -1000 then 'G'
            end as grade,

            case
               when pay_late between -1000 and 0  then 'on time'
               when pay_late between 1 and 7 then '1-7'
               when pay_late between 8 and 15 then '8-15'
               when pay_late between 16 and 30 then '16-30'
               when pay_late between 31 and 60 then '31-60'
               when pay_late between 61 and 90 then '61-90'
               when pay_late > 90 then '> 90'
               when pay_late < -1000 then '> 90'
            end as grade_text
            from (
          select cust_no, AVG(pay_late) as pay_late,  sum(amount) as payment_amount
          from (
          select *, DATEDIFF(day,invc_due_date,payment_date) as pay_late
          from (
          select [Entry No_] as entry_no, [Customer No_] as cust_no, tbl_payment.[Document No_] as payment_doc,tbl_payment.[Posting Date] as payment_date,
          amount, tbl_applied.[Document No_] as payment_doc2, invc_date, invc_due_date, invc_closed_date
          from (
          select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date], sum(tbl_d.[Amount (LCY)])*-1 as amount
          from (
          select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
          where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

          inner join (
          select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
          from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
          where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
          on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date]) as tbl_payment

          left join (
          select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
          from (
          select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
          from (
          select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
          from (
          select [Document No_],[Cust_ Ledger Entry No_]
          from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
          where [Document No_] in (
          select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
          where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
          inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
          on(tbl_payment.[Document No_]=tbl_applied.[Document No_]) where tbl_applied.[Document No_] is not null

          union

          select [Entry No_] as entry_no,[Customer No_] as cust_no,tbl.[Document No_] as payment_doc,tbl.[Posting Date] as payment_date,
          tbl.amount as amount,tbl_a.payment_doc as payment_doc2, invc_date, invc_due_date, invc_closed_date
          from (
          select [Entry No_], [Customer No_],tbl_payment.[Document No_], tbl_payment.amount, tbl_payment.[Posting Date] from (
          select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date], sum(tbl_d.[Amount (LCY)])*-1 as amount
          from (
          select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
          where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

          inner join (
          select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
          from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
          where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
          on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date]) as tbl_payment

          left join (
          select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
          from (
          select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
          from (
          select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
          from (
          select [Document No_],[Cust_ Ledger Entry No_]
          from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
          where [Document No_] in (
          select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
          where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
          inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
          on(tbl_payment.[Document No_]=tbl_applied.[Document No_]) where tbl_applied.[Document No_] is null) as tbl

          inner join (
          select tbl.[Cust_ Ledger Entry No_], tbl.invc_no, tbl.payment_doc, tbl_a.[Posting Date] as invc_date, tbl_a.[Due Date] as invc_due_date, tbl_a.[Closed at Date] as invc_closed_date
          from (
          select tbl.[Cust_ Ledger Entry No_], tbl.invc_no, tbl_a.[Document No_] as payment_doc
          from(
          select [Cust_ Ledger Entry No_], [Document No_] as invc_no
          from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in (
          select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where [Document No_] in (
          select distinct tbl_payment.[Document No_] from (
          select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_], sum(tbl_d.[Amount (LCY)])*-1 as amount
          from (
          select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
          where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

          inner join (
          select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
          from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
          where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
          on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_]) as tbl_payment

          left join (
          select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
          from (
          select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
          from (
          select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
          from (
          select [Document No_],[Cust_ Ledger Entry No_]
          from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
          where [Document No_] in (
          select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
          where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
          inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
          on(tbl_payment.[Document No_]=tbl_applied.[Document No_])  where tbl_applied.[Document No_] is null)) and [Entry Type]='2' and [Document Type]='2') as tbl
          inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] tbl_a on(tbl.[Cust_ Ledger Entry No_]=tbl_a.[Entry No_])) as tbl
          inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] tbl_a on(tbl_a.[Document No_]=tbl.invc_no)) as tbl_a
          on(tbl.[Document No_]=tbl_a.payment_doc)) as tbl) as tbl group by cust_no) as tbl) as tbl group by grade, grade_text order by grade;";
*/

          $query_temp = "select grade, grade_text, count(grade) as grade_count,  sum(payment_amount) as payment_amount
            from (
            select *,
              case
                  when pay_late between -1000 and 0  then 'A'
                  when pay_late between 1 and 7 then 'B'
                  when pay_late between 8 and 15 then 'C'
                  when pay_late between 16 and 30 then 'D'
                  when pay_late between 31 and 60 then 'E'
                  when pay_late between 61 and 90 then 'F'
                  when pay_late > 90 then 'G'
                  when pay_late < -1000 then 'G'
              end as grade,

              case
                 when pay_late between -1000 and 0  then 'on time'
                 when pay_late between 1 and 7 then '1-7'
                 when pay_late between 8 and 15 then '8-15'
                 when pay_late between 16 and 30 then '16-30'
                 when pay_late between 31 and 60 then '31-60'
                 when pay_late between 61 and 90 then '61-90'
                 when pay_late > 90 then '> 90'
                 when pay_late < -1000 then '> 90'
              end as grade_text
              from (
            select cust_no, AVG(pay_late) as pay_late,  sum(amount) as payment_amount
            from (
            select *, DATEDIFF(day,invc_due_date,payment_date) as pay_late
            from (
            select cust_no, payment_doc,payment_date,payment_doc2, invc_date, invc_due_date, invc_closed_date, sum(amount) as amount
            from (
            select * from (
            select [Entry No_] as entry_no, [Customer No_] as cust_no, tbl_payment.[Document No_] as payment_doc,tbl_payment.[Posting Date] as payment_date,
            amount, tbl_applied.[Document No_] as payment_doc2, invc_date, invc_due_date, invc_closed_date
            from (
            select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date], sum(tbl_d.[Amount (LCY)])*-1 as amount
            from (
            select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

            inner join (
            select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
            on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date]) as tbl_payment

            left join (
            select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
            from (
            select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
            from (
            select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
            from (
            select [Document No_],[Cust_ Ledger Entry No_]
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
            where [Document No_] in (
            select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
            inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
            on(tbl_payment.[Document No_]=tbl_applied.[Document No_]) where tbl_applied.[Document No_] is not null

            union

            select [Entry No_] as entry_no,[Customer No_] as cust_no,tbl.[Document No_] as payment_doc,tbl.[Posting Date] as payment_date,
            tbl.amount as amount,tbl_a.payment_doc as payment_doc2, invc_date, invc_due_date, invc_closed_date
            from (
            select [Entry No_], [Customer No_],tbl_payment.[Document No_], tbl_payment.amount, tbl_payment.[Posting Date] from (
            select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date], sum(tbl_d.[Amount (LCY)])*-1 as amount
            from (
            select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

            inner join (
            select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
            on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date]) as tbl_payment

            left join (
            select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
            from (
            select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
            from (
            select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
            from (
            select [Document No_],[Cust_ Ledger Entry No_]
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
            where [Document No_] in (
            select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
            inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
            on(tbl_payment.[Document No_]=tbl_applied.[Document No_]) where tbl_applied.[Document No_] is null) as tbl

            inner join (
            select tbl.[Cust_ Ledger Entry No_], tbl.invc_no, tbl.payment_doc, tbl_a.[Posting Date] as invc_date, tbl_a.[Due Date] as invc_due_date, tbl_a.[Closed at Date] as invc_closed_date
            from (
            select tbl.[Cust_ Ledger Entry No_], tbl.invc_no, tbl_a.[Document No_] as payment_doc
            from(
            select [Cust_ Ledger Entry No_], [Document No_] as invc_no
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in (
            select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where [Document No_] in (
            select distinct tbl_payment.[Document No_] from (
            select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_], sum(tbl_d.[Amount (LCY)])*-1 as amount
            from (
            select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

            inner join (
            select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
            on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_]) as tbl_payment

            left join (
            select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
            from (
            select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
            from (
            select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
            from (
            select [Document No_],[Cust_ Ledger Entry No_]
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
            where [Document No_] in (
            select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
            inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
            on(tbl_payment.[Document No_]=tbl_applied.[Document No_])  where tbl_applied.[Document No_] is null)) and [Entry Type]='2' and [Document Type]='2') as tbl
            inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] tbl_a on(tbl.[Cust_ Ledger Entry No_]=tbl_a.[Entry No_])) as tbl
            inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] tbl_a on(tbl_a.[Document No_]=tbl.invc_no)) as tbl_a on(tbl.[Document No_]=tbl_a.payment_doc)) as tbl

            union

            select tbl.[Entry No_] as entry_no,tbl.[Customer No_] as cust_no,tbl.payment_doc, tbl.[Posting Date] as payment_date, detcustleden.[Amount (LCY)]*-1  as amount,
            tbl.payment_doc as payment_doc2, '' as invc_date,'' as invc_due_date,'' as invc_closed_date
            from (
            select tbl.[Entry No_],tbl.[Closed by Entry No_],custleden.[Document No_] as payment_doc, custleden.[Posting Date], custleden.[Customer No_]
            from (
            select [Entry No_], [Closed by Entry No_]
            from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where [Closed by Entry No_] in (select [Cust_ Ledger Entry No_]
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%')
            and [Amount (LCY)]<0 and [Entry Type]='1') and [Document Type]='6') as tbl
            inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] as custleden on(custleden.[Entry No_]=tbl.[Closed by Entry No_])) as tbl
            inner join [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] as detcustleden on(detcustleden.[Cust_ Ledger Entry No_]=tbl.[Entry No_]) and [Document Type]='6'
            and
            tbl.[Closed by Entry No_] in (select [Entry No_]
            from (
            select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date], sum(tbl_d.[Amount (LCY)])*-1 as amount
            from (
            select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

            inner join (
            select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
            on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date]) as tbl_payment

            left join (
            select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
            from (
            select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
            from (
            select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
            from (
            select [Document No_],[Cust_ Ledger Entry No_]
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
            where [Document No_] in (
            select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
            inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
            on(tbl_payment.[Document No_]=tbl_applied.[Document No_]) where tbl_applied.[Document No_] is not null

            union

            select [Entry No_]
            from (
            select [Entry No_], [Customer No_],tbl_payment.[Document No_], tbl_payment.amount, tbl_payment.[Posting Date] from (
            select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date], sum(tbl_d.[Amount (LCY)])*-1 as amount
            from (
            select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

            inner join (
            select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
            on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_],tbl_h.[Posting Date]) as tbl_payment

            left join (
            select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
            from (
            select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
            from (
            select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
            from (
            select [Document No_],[Cust_ Ledger Entry No_]
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
            where [Document No_] in (
            select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
            inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
            on(tbl_payment.[Document No_]=tbl_applied.[Document No_]) where tbl_applied.[Document No_] is null) as tbl

            inner join (
            select tbl.[Cust_ Ledger Entry No_], tbl.invc_no, tbl.payment_doc, tbl_a.[Posting Date] as invc_date, tbl_a.[Due Date] as invc_due_date, tbl_a.[Closed at Date] as invc_closed_date
            from (
            select tbl.[Cust_ Ledger Entry No_], tbl.invc_no, tbl_a.[Document No_] as payment_doc
            from(
            select [Cust_ Ledger Entry No_], [Document No_] as invc_no
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in (
            select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where [Document No_] in (
            select distinct tbl_payment.[Document No_] from (
            select tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_], sum(tbl_d.[Amount (LCY)])*-1 as amount
            from (
            select [Entry No_], [Customer No_], [Posting Date],[Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') as tbl_h

            inner join (
            select [Entry No_], [Cust_ Ledger Entry No_], [Amount (LCY)]
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [Cust_ Ledger Entry No_] in(select [Entry No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [Amount (LCY)]<0 and [Entry Type]='1') as tbl_d
            on(tbl_h.[Entry No_]=tbl_d.[Cust_ Ledger Entry No_]) group by tbl_h.[Entry No_], tbl_h.[Customer No_], tbl_h.[Document No_]) as tbl_payment

            left join (
            select  [Document No_], max(invc_date)as invc_date, max(invc_due_date) as invc_due_date, max(invc_closed_date) as invc_closed_date
            from (
            select distinct [Document No_], invc_date, invc_due_date, invc_closed_date
            from (
            select tbl_detail_cust_led.[Document No_], d.[Posting Date] as invc_date, d.[Document No_] as invc_no, d.[Due Date] as invc_due_date, d.[Closed at Date] as invc_closed_date
            from (
            select [Document No_],[Cust_ Ledger Entry No_]
            from [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry]
            where [Document No_] in (
            select [Document No_] from [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry]
            where [Document No_] like '2127-".$year.$month."%' or [Document No_] like '7209-".$year.$month."%' or [Document No_] like '8944-".$year.$month."%') and [document type]='1' and [Initial Document Type]='2') as tbl_detail_cust_led
            inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] d on(tbl_detail_cust_led.[Cust_ Ledger Entry No_]=d.[Entry No_])) as tbl ) as tbl group by [Document No_]) as tbl_applied
            on(tbl_payment.[Document No_]=tbl_applied.[Document No_])  where tbl_applied.[Document No_] is null)) and [Entry Type]='2' and [Document Type]='2') as tbl
            inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] tbl_a on(tbl.[Cust_ Ledger Entry No_]=tbl_a.[Entry No_])) as tbl
            inner join [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] tbl_a on(tbl_a.[Document No_]=tbl.invc_no)) as tbl_a on(tbl.[Document No_]=tbl_a.payment_doc))) as tbl
            group by cust_no, payment_doc,payment_date,payment_doc2, invc_date, invc_due_date, invc_closed_date) as tbl) as tbl group by cust_no)as tbl) as tbl group by grade, grade_text order by grade;";

        $query = $db->query($query_temp);
        return $query->result_array();
      }
}

?>
