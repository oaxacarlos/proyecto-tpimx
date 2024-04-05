<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_jobs extends CI_Model{

    function get_sales_invoice_header_nav($date_from, $date_to){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "SELECT
    	  [No_]
          ,[Sell-to Customer No_]
          ,[Bill-to Customer No_]
          ,[Bill-to Name]
          ,[Bill-to Name 2]
          ,[Bill-to Address]
          ,[Bill-to Address 2]
          ,[Bill-to City]
          ,[Bill-to Contact]
          ,[Ship-to Code]
          ,[Ship-to Name]
          ,[Ship-to Name 2]
          ,[Ship-to Address]
          ,[Ship-to Address 2]
          ,[Ship-to City]
          ,[Ship-to Contact]
          ,[Order Date]
          ,[Posting Date]
          ,[Shipment Date]
          ,[Posting Description]
          ,[Payment Terms Code]
          ,[Due Date]
          ,[Payment Discount _]
          ,[Pmt_ Discount Date]
          ,[Shipment Method Code]
          ,[Location Code]
          ,[Customer Posting Group]
          ,[Currency Code]
          ,[Currency Factor]
          ,[Customer Price Group]
          ,[Prices Including VAT]
          ,[Invoice Disc_ Code]
          ,[Customer Disc_ Group]
          ,[Language Code]
          ,[Salesperson Code]
          ,[Order No_]
          ,[No_ Printed]
          ,[On Hold]
          ,[Applies-to Doc_ Type]
          ,[Applies-to Doc_ No_]
          ,[Bal_ Account No_]
          ,[VAT Registration No_]
          ,[Reason Code]
          ,[Gen_ Bus_ Posting Group]
          ,[VAT Country_Region Code]
          ,[Sell-to Customer Name]
          ,[Sell-to Customer Name 2]
          ,[Sell-to Address]
          ,[Sell-to Address 2]
          ,[Sell-to City]
          ,[Sell-to Contact]
          ,[Bill-to Post Code]
          ,[Bill-to County]
          ,[Bill-to Country_Region Code]
          ,[Sell-to Post Code]
          ,[Sell-to County]
          ,[Sell-to Country_Region Code]
          ,[Ship-to Post Code]
          ,[Ship-to County]
          ,[Ship-to Country_Region Code]
          ,[Document Date]
          ,[External Document No_]
          ,[Payment Method Code]
          ,[User ID]
          ,[VAT Bus_ Posting Group]
          ,[Cust_ Ledger Entry No_]
          ,[Your Reference]
      FROM [".$this->config->item('sqlserver_live')."Sales Invoice Header] where [posting date] between '".$date_from."' and '".$date_to."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function insert_into_sales_invoice_header_nav_local($result){

        foreach($result as $row){
          $data = array(
            "no" => $row["No_"],
            "sell_to_customer_no" => $row["Sell-to Customer No_"],
            "bill_to_customer_no" =>  $row["Bill-to Customer No_"],
            "bill_to_name" => $row["Bill-to Name"],
            "bill_to_name2" => $row["Bill-to Name 2"],
            "bill_to_address" => $row["Bill-to Address"],
            "bill_to_address2" => $row["Bill-to Address 2"],
            "bill_to_city" => $row["Bill-to City"],
            "bill_to_contact" => $row["Bill-to Contact"],
            "ship_to_code" => $row["Ship-to Code"],
            "ship_to_name" => $row["Ship-to Name"],
            "ship_to_name2" => $row["Ship-to Name 2"],
            "ship_to_address" => $row["Ship-to Address"],
            "ship_to_address2" => $row["Ship-to Address 2"],
            "ship_to_city" => $row["Ship-to City"],
            "ship_to_contact" => $row["Ship-to Contact"],
            "order_date" => $row["Order Date"],
            "posting_date" => $row["Posting Date"],
            "shipment_date" => $row["Shipment Date"],
            "posting_description" => $row["Posting Description"],
            "payment_terms_code" => $row["Payment Terms Code"],
            "due_date" => $row["Due Date"],
            "payment_discount" => $row["Payment Discount _"],
            "pmt_discount_date" => $row["Pmt_ Discount Date"],
            "shipment_method_code" => $row["Shipment Method Code"],
            "location_code" => $row["Location Code"],
            "customer_posting_group" => $row["Customer Posting Group"],
            "currency_code" => $row["Currency Code"],
            "currency_factor" => $row["Currency Factor"],
            "customer_price_group" => $row["Customer Price Group"],
            "invoice_disc_code" => $row["Invoice Disc_ Code"],
            "vat_registration_no" => $row["VAT Registration No_"],
            "gen_bus_posting_group" => $row["Gen_ Bus_ Posting Group"],
            "vat_country_region_code" => $row["VAT Country_Region Code"],
            "sell_to_customer_name" => $row["Sell-to Customer Name"],
            "sell_to_customer_name2" => $row["Sell-to Customer Name 2"],
            "sell_to_address" => $row["Sell-to Address"],
            "sell_to_address2" => $row["Sell-to Address 2"],
            "sell_to_city" => $row["Sell-to City"],
            "sell_to_contact" => $row["Sell-to Contact"],
            "bill_to_post_code" => $row["Bill-to Post Code"],
            "bill_to_county" => $row["Bill-to County"],
            "bill_to_country_region_code" => $row["Bill-to Country_Region Code"],
            "sell_to_post_code" => $row["Sell-to Post Code"],
            "sell_to_county" => $row["Sell-to County"],
            "sell_to_country_region_code" => $row["Sell-to Country_Region Code"],
            "ship_to_post_code" => $row["Ship-to Post Code"],
            "ship_to_county" => $row["Ship-to County"],
            "document_date" => $row["Document Date"],
            "external_document_no" => $row["External Document No_"],
            "payment_method_code" => $row["Payment Method Code"],
            "user_id" => $row["User ID"],
            "vat_bus_posting_group" => $row["VAT Bus_ Posting Group"],
            "cust_ledger_entry_no" => $row["Cust_ Ledger Entry No_"],
            "sales_person_code" => $row["Salesperson Code"],
            "your_ref" => $row["Your Reference"],
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('sales_invoice_header', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $db->query($insert_query);
        }

    }
    //---

    function get_sales_invoice_line_nav($date_from, $date_to){
          $db = $this->load->database('sql_server_live', true);
          $query_temp = "SELECT
              [Document No_]
            ,[Line No_]
            ,[Sell-to Customer No_]
            ,[Type]
            ,[No_]
            ,[Location Code]
            ,[Posting Group]
            ,[Shipment Date]
            ,[Description]
            ,[Description 2]
            ,[Unit of Measure]
            ,[Quantity]
            ,[Unit Price]
            ,[Unit Cost (LCY)]
            ,[VAT _]
            ,[Line Discount _]
            ,[Line Discount Amount]
            ,[Amount]
            ,[Amount Including VAT]
            ,[Gross Weight]
            ,[Net Weight]
            ,[Customer Price Group]
            ,[Shipment No_]
            ,[Shipment Line No_]
            ,[Bill-to Customer No_]
            ,[Gen_ Bus_ Posting Group]
            ,[Gen_ Prod_ Posting Group]
            ,[VAT Bus_ Posting Group]
            ,[VAT Prod_ Posting Group]
            ,[VAT Base Amount]
            ,[Unit Cost]
            ,[Line Amount]
            ,[Posting Date]
            ,[Bin Code]
            ,[Qty_ per Unit of Measure]
            ,[Unit of Measure Code]
            ,[Quantity (Base)]
            ,[Item Category Code]
            ,[Product Group Code]

        FROM [".$this->config->item('sqlserver_live')."Sales Invoice Line] where [posting date] between '".$date_from."' and '".$date_to."' and [Quantity]>0;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function insert_into_sales_invoice_line_nav_local($result){
        foreach($result as $row){
          $data = array(
            "document_no" => $row["Document No_"],
            "line_no" => $row["Line No_"],
            "sell_to_customer_no" => $row["Sell-to Customer No_"],
            "type" => $row["Type"],
            "no" => $row["No_"],
            "location_code" => $row["Location Code"],
            "posting_group" => $row["Posting Group"],
            "shipment_date" => $row["Shipment Date"],
            "description" => $row["Description"],
            "description2" => $row["Description 2"],
            "unit_of_measure" => $row["Unit of Measure"],
            "quantity" => $row["Quantity"],
            "unit_price" => $row["Unit Price"],
            "unit_cost_lcy" => $row["Unit Cost (LCY)"],
            "vat" => $row["VAT _"],
            "line_discount" => $row["Line Discount _"],
            "line_discount_amount" => $row["Line Discount Amount"],
            "amount" => $row["Amount"],
            "amount_including_vat" => $row["Amount Including VAT"],
            "gross_weight" => $row["Gross Weight"],
            "net_weight" => $row["Net Weight"],
            "bill_to_customer_no" => $row["Bill-to Customer No_"],
            "gen_bus_posting_group" => $row["Gen_ Bus_ Posting Group"],
            "gen_prod_posting_group" => $row["Gen_ Prod_ Posting Group"],
            "vat_bus_posting_group" => $row["VAT Bus_ Posting Group"],
            "vat_prod_posting_group" => $row["VAT Prod_ Posting Group"],
            "vat_base_amount" => $row["VAT Base Amount"],
            "unit_cost" => $row["Unit Cost"],
            "line_amount" => $row["Line Amount"],
            "posting_date" => $row["Posting Date"],
            "bin_code" => $row["Bin Code"],
            "qty_per_unit_of_measure" => $row["Qty_ per Unit of Measure"],
            "unit_of_measure_code" => $row["Unit of Measure Code"],
            "quantity_base" => $row["Quantity (Base)"],
            "item_category_code" => $row["Item Category Code"],
            "product_group_code" => $row["Product Group Code"],
            //"code_prod_serv_sat" => $row["Cod_ Prod_ Serv_ SAT_"],
            "shipment_no" => $row["Shipment No_"],
            "shipment_line_no" => $row["Shipment Line No_"],
          );

            $db = $this->load->database('default2', true);
            $insert_query = $this->db->insert_string('sales_invoice_line', $data);
            $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
            $db->query($insert_query);
        }
    }
    //--

    function get_sales_cm_header_nav($date_from, $date_to){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "SELECT
    	  [No_]
          ,[Sell-to Customer No_]
          ,[Bill-to Customer No_]
          ,[Bill-to Name]
          ,[Bill-to Name 2]
          ,[Bill-to Address]
          ,[Bill-to Address 2]
          ,[Bill-to City]
          ,[Bill-to Contact]
          ,[Ship-to Code]
          ,[Ship-to Name]
          ,[Ship-to Name 2]
          ,[Ship-to Address]
          ,[Ship-to Address 2]
          ,[Ship-to City]
          ,[Ship-to Contact]
          ,[Posting Date]
          ,[Shipment Date]
          ,[Posting Description]
          ,[Payment Terms Code]
          ,[Due Date]
          ,[Payment Discount _]
          ,[Pmt_ Discount Date]
          ,[Shipment Method Code]
          ,[Location Code]
          ,[Customer Posting Group]
          ,[Currency Code]
          ,[Currency Factor]
          ,[Customer Price Group]
          ,[Prices Including VAT]
          ,[Invoice Disc_ Code]
          ,[Customer Disc_ Group]
          ,[Language Code]
          ,[Salesperson Code]
          ,[Applies-to Doc_ Type]
          ,[Applies-to Doc_ No_]
          ,[Bal_ Account No_]
          ,[VAT Registration No_]
          ,[Reason Code]
          ,[Gen_ Bus_ Posting Group]
          ,[VAT Country_Region Code]
          ,[Sell-to Customer Name]
          ,[Sell-to Customer Name 2]
          ,[Sell-to Address]
          ,[Sell-to Address 2]
          ,[Sell-to City]
          ,[Sell-to Contact]
          ,[Bill-to Post Code]
          ,[Bill-to County]
          ,[Bill-to Country_Region Code]
          ,[Sell-to Post Code]
          ,[Sell-to County]
          ,[Sell-to Country_Region Code]
          ,[Ship-to Post Code]
          ,[Ship-to County]
          ,[Ship-to Country_Region Code]
          ,[Document Date]
          ,[External Document No_]
          ,[Payment Method Code]
          ,[User ID]
          ,[VAT Bus_ Posting Group]
          ,[Cust_ Ledger Entry No_]
          ,[Applies-to Doc_ Type]
          ,[Applies-to Doc_ No_]
          FROM [".$this->config->item('sqlserver_live')."Sales Cr_Memo Header] where [posting date] between '".$date_from."' and '".$date_to."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function insert_into_sales_cm_header_nav_local($result){

        foreach($result as $row){
          $data = array(
            "no" => $row["No_"],
            "sell_to_customer_no" => $row["Sell-to Customer No_"],
            "bill_to_customer_no" =>  $row["Bill-to Customer No_"],
            "bill_to_name" => $row["Bill-to Name"],
            "bill_to_name2" => $row["Bill-to Name 2"],
            "bill_to_address" => $row["Bill-to Address"],
            "bill_to_address2" => $row["Bill-to Address 2"],
            "bill_to_city" => $row["Bill-to City"],
            "bill_to_contact" => $row["Bill-to Contact"],
            "ship_to_code" => $row["Ship-to Code"],
            "ship_to_name" => $row["Ship-to Name"],
            "ship_to_name2" => $row["Ship-to Name 2"],
            "ship_to_address" => $row["Ship-to Address"],
            "ship_to_address2" => $row["Ship-to Address 2"],
            "ship_to_city" => $row["Ship-to City"],
            "ship_to_contact" => $row["Ship-to Contact"],
            "posting_date" => $row["Posting Date"],
            "shipment_date" => $row["Shipment Date"],
            "posting_description" => $row["Posting Description"],
            "payment_terms_code" => $row["Payment Terms Code"],
            "due_date" => $row["Due Date"],
            "payment_discount" => $row["Payment Discount _"],
            "pmt_discount_date" => $row["Pmt_ Discount Date"],
            "shipment_method_code" => $row["Shipment Method Code"],
            "location_code" => $row["Location Code"],
            "customer_posting_group" => $row["Customer Posting Group"],
            "currency_code" => $row["Currency Code"],
            "currency_factor" => $row["Currency Factor"],
            "customer_price_group" => $row["Customer Price Group"],
            "invoice_disc_code" => $row["Invoice Disc_ Code"],
            "vat_registration_no" => $row["VAT Registration No_"],
            "gen_bus_posting_group" => $row["Gen_ Bus_ Posting Group"],
            "vat_country_region_code" => $row["VAT Country_Region Code"],
            "sell_to_customer_name" => $row["Sell-to Customer Name"],
            "sell_to_customer_name2" => $row["Sell-to Customer Name 2"],
            "sell_to_address" => $row["Sell-to Address"],
            "sell_to_address2" => $row["Sell-to Address 2"],
            "sell_to_city" => $row["Sell-to City"],
            "sell_to_contact" => $row["Sell-to Contact"],
            "bill_to_post_code" => $row["Bill-to Post Code"],
            "bill_to_county" => $row["Bill-to County"],
            "bill_to_country_region_code" => $row["Bill-to Country_Region Code"],
            "sell_to_post_code" => $row["Sell-to Post Code"],
            "sell_to_county" => $row["Sell-to County"],
            "sell_to_country_region_code" => $row["Sell-to Country_Region Code"],
            "ship_to_post_code" => $row["Ship-to Post Code"],
            "ship_to_county" => $row["Ship-to County"],
            "document_date" => $row["Document Date"],
            "external_document_no" => $row["External Document No_"],
            "payment_method_code" => $row["Payment Method Code"],
            "user_id" => $row["User ID"],
            "vat_bus_posting_group" => $row["VAT Bus_ Posting Group"],
            "cust_ledger_entry_no" => $row["Cust_ Ledger Entry No_"],
            "sales_person_code" => $row["Salesperson Code"],
            "applies_to_doc_no" => $row["Applies-to Doc_ Type"],
            "applies_to_doc_type" => $row["Applies-to Doc_ No_"],
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('sales_cr_memo_header', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $db->query($insert_query);
        }

    }
    //---

    function get_sales_cm_line_nav($date_from, $date_to){
          $db = $this->load->database('sql_server_live', true);
          $query_temp = "SELECT
              [Document No_]
            ,[Line No_]
            ,[Sell-to Customer No_]
            ,[Type]
            ,[No_]
            ,[Location Code]
            ,[Posting Group]
            ,[Shipment Date]
            ,[Description]
            ,[Description 2]
            ,[Unit of Measure]
            ,[Quantity]
            ,[Unit Price]
            ,[Unit Cost (LCY)]
            ,[VAT _]
            ,[Line Discount _]
            ,[Line Discount Amount]
            ,[Amount]
            ,[Amount Including VAT]
            ,[Gross Weight]
            ,[Net Weight]
            ,[Customer Price Group]
            ,[Bill-to Customer No_]
            ,[Gen_ Bus_ Posting Group]
            ,[Gen_ Prod_ Posting Group]
            ,[VAT Bus_ Posting Group]
            ,[VAT Prod_ Posting Group]
            ,[VAT Base Amount]
            ,[Unit Cost]
            ,[Line Amount]
            ,[Posting Date]
            ,[Bin Code]
            ,[Qty_ per Unit of Measure]
            ,[Unit of Measure Code]
            ,[Quantity (Base)]
            ,[Item Category Code]
            ,[Product Group Code]
            ,[Appl_-to Item Entry]
        FROM [".$this->config->item('sqlserver_live')."Sales Cr_Memo Line] where [posting date] between '".$date_from."' and '".$date_to."' and [Quantity]>0;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function insert_into_sales_cm_line_nav_local($result){
        foreach($result as $row){
          $data = array(
            "document_no" => $row["Document No_"],
            "line_no" => $row["Line No_"],
            "sell_to_customer_no" => $row["Sell-to Customer No_"],
            "type" => $row["Type"],
            "no" => $row["No_"],
            "location_code" => $row["Location Code"],
            "posting_group" => $row["Posting Group"],
            "shipment_date" => $row["Shipment Date"],
            "description" => $row["Description"],
            "description2" => $row["Description 2"],
            "unit_of_measure" => $row["Unit of Measure"],
            "quantity" => $row["Quantity"],
            "unit_price" => $row["Unit Price"],
            "unit_cost_lcy" => $row["Unit Cost (LCY)"],
            "vat" => $row["VAT _"],
            "line_discount" => $row["Line Discount _"],
            "line_discount_amount" => $row["Line Discount Amount"],
            "amount" => $row["Amount"],
            "amount_including_vat" => $row["Amount Including VAT"],
            "gross_weight" => $row["Gross Weight"],
            "net_weight" => $row["Net Weight"],
            "bill_to_customer_no" => $row["Bill-to Customer No_"],
            "gen_bus_posting_group" => $row["Gen_ Bus_ Posting Group"],
            "gen_prod_posting_group" => $row["Gen_ Prod_ Posting Group"],
            "vat_bus_posting_group" => $row["VAT Bus_ Posting Group"],
            "vat_prod_posting_group" => $row["VAT Prod_ Posting Group"],
            "vat_base_amount" => $row["VAT Base Amount"],
            "unit_cost" => $row["Unit Cost"],
            "line_amount" => $row["Line Amount"],
            "posting_date" => $row["Posting Date"],
            "bin_code" => $row["Bin Code"],
            "qty_per_unit_of_measure" => $row["Qty_ per Unit of Measure"],
            "unit_of_measure_code" => $row["Unit of Measure Code"],
            "quantity_base" => $row["Quantity (Base)"],
            "item_category_code" => $row["Item Category Code"],
            "product_group_code" => $row["Product Group Code"],
            //"code_prod_serv_sat" => $row["Cod_ Prod, Serv_ SAT_"],
            "appli_to_item_entry" => $row["Appl_-to Item Entry"],
          );

            $db = $this->load->database('default2', true);
            $insert_query = $this->db->insert_string('sales_cr_memo_line', $data);
            $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
            $db->query($insert_query);
        }
    }
    //--

    function get_customer_nav(){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select [No_], [Name], [Name 2], [Address], [Address 2], [City], [Contact],[Phone No_], [country_region code],
          [VAt Registration No_], [Post Code], [County], [E-Mail], [Salesperson Code], [Payment Terms Code], [Service Zone Code]
          FROM [".$this->config->item('sqlserver_live')."Customer]

          union

          select [Code] as [No_], [Name], [Name 2], [Address], [Address 2], [City], [Contact],[Phone No_], [country_region code],
          '' as [VAt Registration No_], [Post Code], [County], [E-Mail], '' as [Salesperson Code], '' as [Payment Terms Code], '' as [Service Zone Code] from [".$this->config->item('sqlserver_live')."Location]";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function insert_into_customer_nav_local($result){
        foreach($result as $row){
          $data = array(
            "cust_no" => $row["No_"],
            "name" => $row["Name"],
            "name2" => $row["Name 2"],
            "address" => $row["Address"],
            "address2" => $row["Address 2"],
            "city" => $row["City"],
            "contact" => $row["Contact"],
            "phone_no" => $row["Phone No_"],
            "country_region_code" => $row["country_region code"],
            "vat_no" => $row["VAt Registration No_"],
            "post_code" => $row["Post Code"],
            "county" => $row["County"],
            "email" => $row["E-Mail"],
            "sales_person_code" => $row["Salesperson Code"],
            "payment_terms_code" => $row["Payment Terms Code"],
            "cs_person" => $row["Service Zone Code"],
          );

            $db = $this->load->database('default', true);
            $insert_query = $this->db->insert_string('mst_cust', $data);
            $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
            $db->query($insert_query);

            $db = $this->load->database('default2', true);
            $insert_query = $this->db->insert_string('mst_cust', $data);
            $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
            $db->query($insert_query);
        }
    }
    //--

    function truncate_customer_local(){

        // truncate on wms database
        $db = $this->load->database('default', true);
        $query_temp = "truncate table mst_cust";
        $query = $db->query($query_temp);
        //--

        // truncate on nav database
        $db = $this->load->database('default2', true);
        $query_temp = "truncate table mst_cust";
        $query = $db->query($query_temp);
        //--

        return true;
    }
    //---

    function get_customer_ledger_entry($lastest_entry_no){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "SELECT
          [Entry No_]
          ,[Customer No_]
          ,[Posting Date]
          ,[Document Type]
          ,[Document No_]
          ,[Description]
          ,[Currency Code]
          ,[Sales (LCY)]
          ,[Profit (LCY)]
          ,[Sell-to Customer No_]
          ,[Customer Posting Group]
          ,[Salesperson Code]
          ,[User ID]
          ,[Due Date]
          ,[Closed by Entry No_]
          ,[Closed at Date]
          ,[Closed by Amount]
          ,[Closed by Amount (LCY)]
          ,[Document Date]
          ,[External Document No_]
          ,[Closed by Currency Amount]
          ,[Recipient Bank Account]
          ,[Transaction No_]
          ,[Payment Method Code]
      FROM [".$this->config->item('sqlserver_live')."Cust_ Ledger Entry] where [entry no_]>='".$lastest_entry_no."' and [entry no_]<'900000000';";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function get_lastest_entry_no_cust_ledger_entry(){
        $db = $this->load->database('default2', true);
        $query_temp = "SELECT entry_no FROM cust_ldgr_ent c order by entry_no desc limit 1;";
        $query = $db->query($query_temp)->row();
        $result = $query->entry_no;
        return $result;
    }
    //---

    function insert_customer_ledger_entry($result){

        foreach($result as $row){
          $data = array(
            "entry_no" => $row["Entry No_"],
            "cust_no" => $row["Customer No_"]
            ,"posting_date" => $row["Posting Date"]
            ,"document_type" => $row["Document Type"]
            ,"document_no" => $row["Document No_"]
            ,"description" => $row["Description"]
            ,"currency_code" => $row["Currency Code"]
            ,"sales_lcy" => $row["Sales (LCY)"]
            ,"profit_lcy" => $row["Profit (LCY)"]
            ,"sell_to_cust_no" => $row["Sell-to Customer No_"]
            ,"cust_posting_group" => $row["Customer Posting Group"]
            ,"sales_person_code" => $row["Salesperson Code"]
            ,"user_id" => $row["User ID"]
            ,"due_date" => $row["Due Date"]
            ,"closed_by_entry_no" => $row["Closed by Entry No_"]
            ,"closed_at_date" => $row["Closed at Date"]
            ,"closed_by_amount" => $row["Closed by Amount"]
            ,"closed_by_amount_lcy" => $row["Closed by Amount (LCY)"]
            ,"document_date" => $row["Document Date"]
            ,"external_document_no" => $row["External Document No_"]
            ,"closed_by_currency_amount" => $row["Closed by Currency Amount"]
            ,"receipient_bank_account" => $row["Recipient Bank Account"]
            ,"transaction_no" => $row["Transaction No_"]
            ,"payment_method_code" => $row["Payment Method Code"]
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('cust_ldgr_ent', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $db->query($insert_query);
        }
    }
    //---

    function get_customer_ledger_entry_detail($lastest_entry_no){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "SELECT
          [Entry No_]
         ,[Cust_ Ledger Entry No_]
         ,[Entry Type]
         ,[Posting Date]
         ,[Document Type]
         ,[Document No_]
         ,[Amount]
         ,[Amount (LCY)]
         ,[Customer No_]
         ,[Currency Code]
         ,[User ID]
         ,[Transaction No_]
         ,[Debit Amount]
         ,[Credit Amount]
         ,[Debit Amount (LCY)]
         ,[Credit Amount (LCY)]
         ,[Applied Cust_ Ledger Entry No_]
      FROM [".$this->config->item('sqlserver_live')."Detailed Cust_ Ledg_ Entry] where [entry no_]>='$lastest_entry_no' and [entry no_]<='900000000';";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function get_lastest_entry_no_cust_ledger_entry_detail(){
        $db = $this->load->database('default2', true);
        $query_temp = "SELECT entry_no FROM cust_ldgr_ent_detail c order by entry_no desc limit 1;";
        $query = $db->query($query_temp)->row();
        $result = $query->entry_no;
        return $result;
    }
    //---

    function insert_customer_ledger_entry_detail($result){

        foreach($result as $row){
          $data = array(
          "entry_no" => $row["Entry No_"]
           ,"cust_ledger_entry_no" => $row["Cust_ Ledger Entry No_"]
           ,"entry_type" => $row["Entry Type"]
           ,"posting_date" => $row["Posting Date"]
           ,"document_type" => $row["Document Type"]
           ,"document_no" => $row["Document No_"]
           ,"amount" => $row["Amount"]
           ,"amount_lcy" => $row["Amount (LCY)"]
           ,"cust_no" => $row["Customer No_"]
           ,"currency_code" => $row["Currency Code"]
           ,"user_id" => $row["User ID"]
           ,"transaction_no" => $row["Transaction No_"]
           ,"debit_amount" => $row["Debit Amount"]
           ,"credit_amount" => $row["Credit Amount"]
           ,"debit_amount_lcy" => $row["Debit Amount (LCY)"]
           ,"credit_amount_lcy" => $row["Credit Amount (LCY)"]
           ,"applied_cust_ledger_entry_no" => $row["Applied Cust_ Ledger Entry No_"]
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('cust_ldgr_ent_detail', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $db->query($insert_query);
        }
    }
    //---

    function get_gl_payment_transporter($lastest_entry_no){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "SELECT
            [Entry No_]
            ,[G_L Account No_]
            ,[Posting Date]
            ,[Document Type]
            ,[Document No_]
            ,[Description]
            ,[Amount]
            ,[User ID]
            ,[Quantity]
            ,[Transaction No_]
            ,[Debit Amount]
            ,[Credit Amount]
            ,[Document Date]
            ,[External Document No_]
            ,[Additional-Currency Amount]
            ,[Add_-Currency Debit Amount]
            ,[Add_-Currency Credit Amount]
            ,[VAT Amount]
            ,[Gen_ Bus_ Posting Group]
            ,[Gen_ Prod_ Posting Group]
            ,[VAT Bus_ Posting Group]
            ,[VAT Prod_ Posting Group]
          FROM [".$this->config->item('sqlserver_live')."G_L Entry] where ([entry no_]>='$lastest_entry_no' and [entry no_]<='900000000')
          and [g_l account no_]='8423' and [document type]='2';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_lastest_gl_entry(){
        $db = $this->load->database('default2', true);
        $query_temp = "SELECT entry_no FROM gl_entry c order by entry_no desc limit 1;";
        $query = $db->query($query_temp)->row();
        $result = $query->entry_no;
        return $result;
    }
    //---

    function insert_gl_payment_transporter($result){

        foreach($result as $row){

          // get cust no from description
          $description = explode("-",$row["Description"]);
          $cust_no = substr($description[1], 0, 7);
          //---

          $data = array(
            "entry_no" =>   $row["Entry No_"]
            ,"gl_account_no" =>  $row["G_L Account No_"]
            ,"posting_date" =>  $row["Posting Date"]
            ,"document_type" =>  $row["Document Type"]
            ,"document_no" =>  $row["Document No_"]
            ,"description" =>  $row["Description"]
            ,"amount" =>  $row["Amount"]
            ,"user_id" =>  $row["User ID"]
            ,"qty" =>  $row["Quantity"]
            ,"transaction_no" =>  $row["Transaction No_"]
            ,"debit_amount" =>  $row["Debit Amount"]
            ,"credit_amount" =>  $row["Credit Amount"]
            ,"document_date" =>  $row["Document Date"]
            ,"external_document_no" =>  $row["External Document No_"]
            ,"add_curr_amount" =>  $row["Additional-Currency Amount"]
            ,"add_curr_debit_amount" =>  $row["Add_-Currency Debit Amount"]
            ,"add_curr_credit_amount" =>  $row["Add_-Currency Credit Amount"]
            ,"vat_amount" =>  $row["VAT Amount"]
            ,"gen_bus_posting_group" =>  $row["Gen_ Bus_ Posting Group"]
            ,"gen_prod_posting_group" =>  $row["Gen_ Prod_ Posting Group"]
            ,"vat_bus_posting_group" =>  $row["VAT Bus_ Posting Group"]
            ,"vat_prod_posting_group" =>  $row["VAT Prod_ Posting Group"]
            ,"cust_no" =>  $cust_no
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('gl_entry', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $db->query($insert_query);
        }
    }
    //---

    function get_backorder_nav($top, $offset){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select top ".$top." * from (
          select h.[Document Date], d.[Document No_], d.[Line No_],d.[No_],h.[Location Code], d.[Outstanding Quantity],
          h.[Sell-to Customer No_],h.[Sell-to Customer Name], h.[External Document No_],d.[Description],d.[Description 2],
          d.[Quantity], d.[Quantity Shipped], d.[Currency Code], d.[Unit Price], d.[Outstanding Amount], d.[Shipment Date],
          YEAR(h.[Document Date]) as year_doc_date, MONTH(h.[Document Date]) as month_doc_date, d.[Item Category Code], h.[Salesperson Code], h.[Sell-to Country_Region Code], h.[Gen_ Bus_ Posting Group],
          h.[Ship-to Address], h.[Ship-to Address 2], h.[Ship-to City], h.[Ship-to Contact], h.[Ship-to County], h.[Ship-to Post Code], h.[Ship-to Country_Region Code]
          from [".$this->config->item('sqlserver_live')."Sales Header] as h inner join [".$this->config->item('sqlserver_live')."Sales Line] as d
          on(h.[No_]=d.[Document No_])
          where h.[Document Type]='1' and [Outstanding Quantity]>0
          order by h.[Document Date],d.[Document No_],d.[Line No_] OFFSET ".$offset." rows) as tbl ";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function insert_backorder($result){
        foreach($result as $row){
          $data = array(
           "document_date" => $row["Document Date"]
           ,"document_no" => $row["Document No_"]
           ,"line_no" => $row["Line No_"]
           ,"item_no" => $row["No_"]
           ,"location_code" => $row["Location Code"]
           ,"qty_outstanding" => $row["Outstanding Quantity"]
           ,"sell_to_customer_no" => $row["Sell-to Customer No_"]
           ,"sell_to_customer_name" => $row["Sell-to Customer Name"]
           ,"external_document_no" => $row["External Document No_"]
           ,"description" => $row["Description"]
           ,"description2" => $row["[Description 2"]
           ,"qty" => $row["Quantity"]
           ,"qty_shipped" => $row["Quantity Shipped"]
           ,"currency_code" => $row["Currency Code"]
           ,"unit_price" => $row["Unit Price"]
           ,"amount_outstanding" => $row["Outstanding Amount"]
           ,"year_doc_date" => $row["year_doc_date"]
           ,"month_doc_date" => $row["month_doc_date"]
           ,"item_category_code" => $row["Item Category Code"]
           ,"salesperson_code" => $row["Salesperson Code"]
           ,"sell_to_country_code" => $row["Sell-to Country_Region Code"]
           ,"gen_bus_posting_group" => $row["Gen_ Bus_ Posting Group"]
           ,"shipment_date" => $row["Shipment Date"]
           ,"ship_to_addr" => $row["Ship-to Address"] // 2023-10-02
           ,"ship_to_addr2" => $row["Ship-to Address 2"] // 2023-10-02
           ,"ship_to_city" => $row["Ship-to City"] // 2023-10-02
           ,"ship_to_contact" => $row["Ship-to Contact"] // 2023-10-02
           ,"ship_to_county" => $row["Ship-to County"] // 2023-10-02
           ,"ship_to_post_code" => $row["Ship-to Post Code"] // 2023-10-02
           ,"ship_to_ctry_code" => $row["Ship-to Country_Region Code"] // 2023-10-02
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('sales_backorder', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $db->query($insert_query);
        }
    }
    //---

    function get_total_row_backorder(){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select count(d.[Document No_]) as total_row
          from [".$this->config->item('sqlserver_live')."Sales Header] as h inner join [".$this->config->item('sqlserver_live')."Sales Line] as d
          on(h.[No_]=d.[Document No_])
          where h.[Document Type]='1' and [Outstanding Quantity]>0 ";
        $query = $db->query($query_temp)->row();
        $result = $query->total_row;
        return $result;
    }
    //----

    function truncate_table_backorder_local(){
        $db = $this->load->database('default2', true);
        $query_temp = "truncate table sales_backorder;";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_remaining_qty_nav($top, $offset){
        $db = $this->load->database('sql_server_live', true);
        /*$query_temp = "select [Item No_],[Location Code], sum([Remaining Quantity]) as qty
          from [".$this->config->item('sqlserver_live')."Item Ledger Entry] where [Remaining Quantity] > 0 and [Location Code] not like 'MX%' group by [Item No_],[Location Code];";*/

        $query_temp = "select top ".$top." * from( select [Item No_],[Location Code], sum([Remaining Quantity]) as qty
                      from [".$this->config->item('sqlserver_live')."Item Ledger Entry]
            		  where [Remaining Quantity] > 0 and [Location Code] not like 'MX%' group by [Item No_],[Location Code]
            		  order by [Item No_] offset ".$offset." rows) as tbl;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function truncate_table_item_invt_nav_local(){
        $db = $this->load->database('default2', true);
        $query_temp = "truncate table item_invt_nav;";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function insert_item_invt_nav($result, $datetime){
        foreach($result as $row){
          $data = array(
           "item_no" => $row["Item No_"]
           ,"qty" => $row["qty"]
           ,"insert_datetime" => $datetime
           ,"location" => $row["Location Code"]
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('item_invt_nav', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
          logs("insert row = ".$row["Item No_"]."|".$row["qty"]." result = ".$result);
        }
    }
    //---

    function get_total_row_items(){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select count([no_]) as total_row from [".$this->config->item('sqlserver_live')."Item] where [Inventory Posting Group]='INVENTORY';";
        $query = $db->query($query_temp)->row();
        $result = $query->total_row;
        return $result;
    }
    //----

    function get_item_nav($top, $offset){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select top ".$top." * from(
          select [No_],[description],[Inventory Posting Group],[Gross Weight], [Net Weight], [Base Unit of Measure],
          [Manufacturer Code], [Item Category Code], [Product Group Code],[Unit Cost],[GTIN] from [".$this->config->item('sqlserver_live')."Item] where [Inventory Posting Group]='INVENTORY'
          order by [no_] OFFSET ".$offset." rows) as tbl";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function insert_item($result){
        foreach($result as $row){
          $data = array(
           "code" => $row["No_"]
           ,"name" => $row["description"]
           ,"type" => $row["Inventory Posting Group"]
           ,"gross_wght" => $row["Gross Weight"]
           ,"net_wght" => $row["Net Weight"]
           ,"uom" => $row["Base Unit of Measure"]
           ,"manufacture_codee" => $row["Manufacturer Code"]
           ,"item_category_codee" => $row["Item Category Code"]
           ,"product_group_codee" => $row["Product Group Code"]
           ,"unit_costt" => $row["Unit Cost"]
           ,"gtin" => $row["GTIN"]
          );

          $db = $this->load->database('default', true);
          $insert_query = $this->db->insert_string('mst_item', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
          logs("insert row = ".$row["No_"]."| result = ".$result);

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('mst_item', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
          logs("insert row = ".$row["No_"]."| result = ".$result);
        }
    }
    //---

    function insert_item_invt_wms($result){
        foreach($result as $row){
          $data = array(
           "item_code" => $row["No_"]
           ,"available" => 0
           ,"picking" => 0
           ,"picked" => 0
           ,"packing" => 0
           ,"extraction" => 0
          );

          $db = $this->load->database('default', true);
          $insert_query = $this->db->insert_string('tsc_item_invt', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
          logs("insert row = ".$row["No_"]."| result = ".$result);
        }
    }
    //---

    function truncate_table_item_local(){

        //$query_temp = "truncate table mst_item;";
        $query_temp = "delete from mst_item where code not in(SELECT item_code FROM tpimx_wms.mst_item_no_delete);";

        $db = $this->load->database('default', true);
        $query = $db->query($query_temp);

        $db = $this->load->database('default2', true);
        $query = $db->query($query_temp);

        return true;
    }
    //---

    function get_warehouse_entry_nav($date){
        $db = $this->load->database('sql_server_live', true);
        //$query_temp = "select [Item No_], sum([Remaining Quantity]) as qty
        //  from [".$this->config->item('sqlserver_live')."Item Ledger Entry] where [Remaining Quantity] > 0 and [Location Code]='WH2' group by [Item No_];";

        $query_temp = "select [Entry No_], [Line No_], [Registering Date], [Location Code], [Bin Code],
          [Item No_], [quantity], [Source Type], [Source Subtype], [Source No_], [Source Line No_],
          [Reference No_], [User ID], [Unit of Measure Code], [Lot No_], [Expiration Date]
          from [".$this->config->item('sqlserver_live')."Warehouse Entry] where [Registering Date]='".$date."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function insert__warehouse_entry($result){
        foreach($result as $row){
          $data = array(
            "entry_no" => $row["Entry No_"],
            "line_no" => $row["Line No_"],
            "registering_date" => $row["Registering Date"],
            "location_code" => $row["Location Code"],
            "bin_code" => $row["Bin Code"],
            "item_no" => $row["Item No_"],
            "qty" => $row["quantity"],
            "source_type" => $row["Source Type"],
            "source_subtype" => $row["Source Subtype"],
            "source_no" => $row["Source No_"],
            "source_line_no" => $row["Source Line No_"],
            "reference_no" => $row["Reference No_"],
            "user_id" => $row["User ID"],
            "uom" => $row["Unit of Measure Code"],
            "lot_no" => $row["Lot No_"],
            "expiration_date" => $row["Expiration Date"],
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('warehouse_entry', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
          logs("insert row = ".$row["No_"]."| result = ".$result);
        }
    }
    //---

    function get_sales_price_from_nav($date){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select [Item No_],[Sales Type], [Sales Code], [Starting Date], [Currency Code],
        [Unit of Measure Code], [Unit Price], [Ending Date]
          FROM [".$this->config->item('sqlserver_live')."Sales Price] where [Starting Date] <= '".$date."' and [Ending Date] >= '".$date."'
          and [Sales Code] in ('1190027','1190033');";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function truncate_table_sales_price(){
        $db = $this->load->database('default2', true);
        $query_temp = "truncate table sales_price;";
        $query = $db->query($query_temp);
    }
    //----

    function insert_sales_price($result){
        foreach($result as $row){
          $data = array(
            "item_code" => $row["Item No_"],
            "sales_type" => $row["Sales Type"],
            "sales_code" => $row["Sales Code"],
            "starting_date" => $row["Starting Date"],
            "currency_code" => $row["Currency Code"],
            "uom" => $row["Unit of Measure Code"],
            "unit_price" => $row["Unit Price"],
            "ending_date" => $row["Ending Date"],
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('sales_price', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
          //logs("insert row = ".$row["No_"]."| result = ".$result);
        }
    }
    //---

    function get_purchase_order_outstanding_transfill_from_nav($top, $offset){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select top ".$top." * from (
          select [Document Type], [Document No_], [Line No_], [Buy-from Vendor No_],[no_],[Location Code],
          [Posting Group], [description], [Unit of Measure], [Outstanding Qty_ (Base)], [Outstanding Quantity],
          [Direct Unit Cost], [Unit Cost (LCY)], [amount], [Amount Including VAT], [Gross Weight], [Net Weight],
          [Outstanding Amount], [Currency Code], [Outstanding Amount (LCY)], [VAT Base Amount],[Unit Cost],
          [Line Amount], [Outstanding Amt_ Ex_ VAT (LCY)]
          from [".$this->config->item('sqlserver_live')."Purchase Line] where [Outstanding Quantity] > 0
          and [Document Type]='1' and [Buy-from Vendor No_] in ('01ATRAINT','01ATRAINT-B')
          order by [Document No_],[Line No_] OFFSET ".$offset." rows) as tbl;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_total_row_purchase_order_outstanding_transfill_from_nav(){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select count([Document No_]) as total_row from [".$this->config->item('sqlserver_live')."Purchase Line] where [Outstanding Quantity] > 0 and [Document Type]='1' and [Buy-from Vendor No_] in ('01ATRAINT','01ATRAINT-B');";
        $query = $db->query($query_temp)->row();
        $result = $query->total_row;
        return $result;
    }
    //----

    function truncate_table_purchase_order_local(){
        $db = $this->load->database('default2', true);
        $query_temp = "truncate table purchase_order;";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function insert_po($result){
        foreach($result as $row){
          $data = array(
            "document_no" => $row["Document No_"],
            "line_no" => $row["Line No_"],
            "document_type" => $row["Document Type"],
            "buy_from_vendor_no" => $row["Buy-from Vendor No_"],
            "no" => $row["no_"],
            "location_code" => $row["Location Code"],
            "posting_group" => $row["Posting Group"],
            "description" => $row["description"],
            "uom" => $row["Unit of Measure"],
            "qty_outstanding" => $row["Outstanding Quantity"],
            "direct_unit_cost" => $row["Direct Unit Cost"],
            "unit_cost_lcy" => $row["Unit Cost (LCY)"],
            "amount" => $row["amount"],
            "amount_include_vat" => $row["Amount Including VAT"],
            "gross_weight" => $row["Gross Weight"],
            "net_weight" => $row["Net Weight"],
            "amount_outstanding" => $row["Outstanding Amount"],
            "currency_code" => $row["Currency Code"],
            "amount_outstanding_lcy" => $row["Outstanding Amount (LCY)"],
            "amount_vat_base" => $row["VAT Base Amount"],
            "unit_cost" => $row["Unit Cost"],
            "line_amount" => $row["Line Amount"],
            "amount_outstanding_ex_vat" => $row["Outstanding Amt_ Ex_ VAT (LCY)"],
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('purchase_order', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
          logs("insert row = ".$row["No_"]."| result = ".$result);
        }
    }
    //---

    function get_fixed_asset_from_nav(){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "Select [No_],[Description],[FA Class Code],[Blocked],[Under Maintenance], [inactive], [FA Posting Group]
        from [".$this->config->item('sqlserver_live')."Fixed Asset]";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function truncate_table_fixed_asset_local(){
        $db = $this->load->database('default2', true);
        $query_temp = "truncate table fixed_asset;";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function insert_fixed_asset($result){
        foreach($result as $row){
          $data = array(
            "code" => $row["No_"],
            "description" => $row["Description"],
            "fa_class_code" => $row["FA Class Code"],
            "blocked" => $row["Blocked"],
            "under_maintenance" => $row["Under Maintenance"],
            "inactive" => $row["inactive"],
            "fa_posting_group" => $row["FA Posting Group"],
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('fixed_asset', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
          logs("insert row = ".$row["No_"]."| result = ".$result);
        }
    }
    //---

    function get_total_row_fixed_asset_from_nav(){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select count([No_]) as total_row from [".$this->config->item('sqlserver_live')."Fixed Asset];";
        $query = $db->query($query_temp)->row();
        $result = $query->total_row;
        return $result;
    }
    //----

    // 2022-11-29 credit note
    function get_credit_note_from_nav($date_from, $date_to, $loc){
        $db = $this->load->database('sql_server', true);
        $query_temp = "select h.[No_] as doc_no,h.[Posting Date] ,h.[Sell-to Customer No_],d.[Location Code],d.[Line No_], d.[No_] as item_code,d.[Description],d.[Quantity], d.[Unit of Measure]
          from [".$this->config->item('sqlserver_pref')."Sales Cr_Memo Header] as h
          inner join [".$this->config->item('sqlserver_pref')."Sales Cr_Memo Line] as d on h.[No_]=d.[Document No_]
          where h.[Posting Date] between '".$date_from."' and '".$date_to."' and [Posting Group] = 'INVENTORY' and  d.[Location Code]='".$loc."';";
          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //--

    function get_row_invt_nav(){
      $db = $this->load->database('sql_server_live', true);
      $query_temp = "select count([Item No_]) as total_row from(
          select [Item No_],[Location Code], sum([Remaining Quantity]) as qty
          from [".$this->config->item('sqlserver_pref')."Item Ledger Entry]
		      where [Remaining Quantity] > 0 and [Location Code] not like 'MX%' group by [Item No_],[Location Code]) as tbl;";
      $query = $db->query($query_temp)->row();
      $result = $query->total_row;
      return $result;
    }
    //----

    // 2023-03-23
    function insert_item_invt_nav_temp($result, $datetime){
        foreach($result as $row){
          $data = array(
           "item_no" => $row["Item No_"]
           ,"qty" => $row["qty"]
           ,"insert_datetime" => $datetime
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('item_invt_nav_temp', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
          logs("insert row = ".$row["Item No_"]."|".$row["qty"]." result = ".$result);
        }
    }
    //---

    // 2023-03-23
    function get_remaining_qty_nav_certain_items($items){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select [Item No_],sum([Remaining Quantity]) as qty
          from [".$this->config->item('sqlserver_live')."Item Ledger Entry] where [Remaining Quantity] > 0 and ([Location Code] not like 'MX%' and [Location Code]!='WH2_QRTN') and [Item No_] in ( ";

        foreach($items as $row){ $query_temp.="'".$row["item_code"]."',"; }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" ) group by [Item No_];";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-03-23
    function get_item_online($type, $cust_no){
        $db = $this->load->database('default2', true);
        $query_temp = "select * FROM mst_item_online where type in (".$type.") and cust_no='".$cust_no."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-03-23
    function truncate_table_item_invt_temp_nav_local(){
        $db = $this->load->database('default2', true);
        $query_temp = "truncate table item_invt_nav_temp;";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    // 2023-03-23
    function get_reservation_certain_items($items){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select * from (
          select [Item No_] as item_code,sum([Quantity (Base)])*-1 as qty from [".$this->config->item('sqlserver_live')."Reservation Entry]
          where [Source Type]='37' and [Source Subtype]='1' and [Item No_] in( ";

        foreach($items as $row){ $query_temp.="'".$row["item_code"]."',"; }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" ) group by [Item No_]) as tbl_a where qty > 0;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-03-23
    function truncate_table_item_resv_nav_local(){
        $db = $this->load->database('default2', true);
        $query_temp = "truncate table item_resv_nav;";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    // 2023-03-23
    function insert_item_resv_nav($result, $datetime){
        foreach($result as $row){
          $data = array(
           "item_code" => $row["item_code"]
           ,"qty" => $row["qty"]
           ,"insert_datetime" => $datetime
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('item_resv_nav', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
          logs("insert row = ".$row["Item No_"]."|".$row["qty"]." result = ".$result);
        }
    }
    //---

    function get_qty_item_online($type){
        $db = $this->load->database('default2', true);

        $minimum = 15;

        $query_temp = "select item_code,item_code2,
          if(qty_avail > qty_max, qty_max, qty_avail) as qty_final
          from (
          select item_code, qty_max, qtyinvt, qtyresv,item_code2,
          if(qtyinvt-qtyresv < ".$minimum.", 0,qtyinvt-qtyresv) as qty_avail
          from(
          SELECT itemonline.item_code, qty_max, itemonline.item_code2,
          if(invt.qty is null,0,invt.qty) as qtyinvt,
          if(resv.qty is null,0,resv.qty) as qtyresv
          FROM (select * from mst_item_online where type in (".$type.") and cust_no='') as itemonline
          left join item_invt_nav_temp invt on(itemonline.item_code = invt.item_no)
          left join item_resv_nav resv on(itemonline.item_code = resv.item_code)) as tbl_a) as tbl_a;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-05-05
    function get_row_so_monthly_nav($from, $to){
      $db = $this->load->database('sql_server_live', true);
      $query_temp = "select count(doc_no) as total_row from (
        select h.[Order Date], h.[No_] as doc_no,h.[Sell-to Customer No_],h.[sell-to customer name],
        d.[Line No_],d.[No_] as item_no,d.[Unit Price],
        d.[Quantity] as qty_order,d.[Quantity]*d.[Unit Price] as qty_order_amount,
        d.[Quantity]-d.[Outstanding Quantity] as qty_proceed,
        (d.[Quantity]-d.[Outstanding Quantity])*d.[Unit Price] as qty_proceed_amount,
        [Outstanding Quantity],
        [Outstanding Quantity]*d.[Unit Price] as amount_outstanding
        from [".$this->config->item('sqlserver_live')."Sales Header] as h
        inner join [".$this->config->item('sqlserver_live')."Sales Line] as d on(d.[Document No_]=h.[No_])
        where [Order Date] between '".$from."' and '".$to."' and h.[Document Type]='1') as tbl_a";
      $query = $db->query($query_temp)->row();
      $result = $query->total_row;
      return $result;
    }
    //----

    // 2023-05-05
    function get_so_monthly_nav($from, $to, $top, $offset){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select top ".$top." * from ( select h.[Order Date], h.[No_] as doc_no, h.[status] as status,h.[Sell-to Customer No_],h.[sell-to customer name],
            d.[Line No_],d.[No_] as item_no,d.[Unit Price],
            d.[Quantity] as qty_order,d.[Quantity]*d.[Unit Price] as order_amount,
            d.[Quantity]-d.[Outstanding Quantity] as qty_proceed,
            (d.[Quantity]-d.[Outstanding Quantity])*d.[Unit Price] as proceed_amount,
            [Outstanding Quantity],
            [Outstanding Quantity]*d.[Unit Price] as amount_outstanding
            from [".$this->config->item('sqlserver_live')."Sales Header] as h
            inner join [".$this->config->item('sqlserver_live')."Sales Line] as d on(d.[Document No_]=h.[No_])
            where [Order Date] between '".$from."' and '".$to."' and h.[Document Type]='1' order by [Document No_],[Line No_] OFFSET ".$offset." rows) as tbl;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-05-05
    function insert_so_monthly_local($result){
        foreach($result as $row){
          $data = array(
           "order_date" => $row["Order Date"]
           ,"doc_no" => $row["doc_no"]
           ,"cust_no" => $row["Sell-to Customer No_"]
           ,"cust_name" => $row["sell-to customer name"]
           ,"line_no" => $row["Line No_"]
           ,"item_no" => $row["item_no"]
           ,"unit_price" => $row["Unit Price"]
           ,"order_qty" => $row["qty_order"]
           ,"order_amount" => $row["order_amount"]
           ,"proceed_qty" => $row["qty_proceed"]
           ,"proceed_amount" => $row["proceed_amount"]
           ,"outstanding_qty" => $row["Outstanding Quantity"]
           ,"outstanding_amount" => $row["amount_outstanding"]
           ,"statuss" => $row["status"]
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('so_detail_monthly', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
        }
    }
    //---

    // 2023-05-05
    function get_row_sls_shipment_so_monthly_nav($from, $to){
      $db = $this->load->database('sql_server_live', true);
      $query_temp = "select count([No_]) as total_row from (
        select [No_], [Order No_],[Posting Date]
        from [".$this->config->item('sqlserver_live')."Sales Shipment Header] where [Posting Date] between '".$from."' and '".$to."'
        and [Order No_]!='') as tbl_a;";
      $query = $db->query($query_temp)->row();
      $result = $query->total_row;
      return $result;
    }
    //----

    // 2023-05-05
    function get_sls_shipment_so_monthly_nav($from, $to, $top, $offset){
        $db = $this->load->database('sql_server_live', true);

        $query_temp = "select top ".$top." * from ( select [No_], [Order No_],[Posting Date]
          from [".$this->config->item('sqlserver_live')."Sales Shipment Header] where [Posting Date] between '".$from."' and '".$to."'
          and [Order No_]!='' order by [Order No_] OFFSET ".$offset." rows) as tbl;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-05-05
    function insert_sls_shipment_so_monthly_local($result){
        foreach($result as $row){
          $data = array(
           "shipment_no" => $row["No_"]
           ,"order_no" => $row["Order No_"]
           ,"posting_date" => $row["Posting Date"]
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('ship_sls_monthly', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
        }
    }
    //---

    // 2023-05-25
    function get_qty_item_online_autotodo($type,$cust_no){
        $db = $this->load->database('default2', true);

        $minimum = 20;

        $query_temp = "select item_code,
        if(qty_avail > 0,'disponible','0') as qty_avail,
          case
          when qty_avail = 0 and estimation_arrived is not null then estimation_arrived
          when qty_avail = 0 and estimation_arrived is null then '2-3 meses'
          else '-'
          end as estimation_arrived

          from (
          select item_code, qty_avail,
          if(tbl_incoming.qty is null,0,tbl_incoming.qty) as qty_incoming, estimation_arrived
          from (
          select item_code,
          if(qty_nav-qty_resv < ".$minimum.",0,qty_nav-qty_resv) as qty_avail
          from (
          select mst_item.item_code,
          if(item_nav.qty is null,0,item_nav.qty) as qty_nav,
          if(item_resv.qty is null,0,item_resv.qty) as qty_resv
          from (
          SELECT item_code FROM mst_item_online m where type=".$type." and cust_no='".$cust_no."') as mst_item
          left join item_invt_nav_temp as item_nav on(item_nav.item_no = mst_item.item_code)
          left join item_resv_nav as item_resv on(item_resv.item_code=mst_item.item_code)) as tbl_a) as tbl_a

          left join(
          SELECT item_no, qty, estimation_arrived
          FROM item_incoming i where statuss='0' group by item_no) as tbl_incoming on(tbl_incoming.item_no=tbl_a.item_code)) as tbl_a";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-06-01
    function insert_item_invt_nav_month_end($result, $datetime, $date){
        foreach($result as $row){
          $data = array(
           "item_no" => $row["Item No_"]
           ,"qty" => $row["qty"]
           ,"insert_datetime" => $datetime
           ,"location" => $row["Location Code"]
           ,"doc_date" => $date
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('item_invt_nav_month_end', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
        }
    }
    //---

    // 2023-10-17
    function get_sls_shipment_header_from_nav($from,$to){
        $db = $this->load->database('sql_server_live', true);

        $query_temp = "select [no_], [Sell-to Customer No_], [Bill-to Customer No_],
          [Sell-to Customer Name], [Sell-to Customer Name 2], [Bill-to Address], [Bill-to Address 2], [Bill-to City],
          [Bill-to Contact], [Ship-to Code], [Ship-to Name], [Ship-to Name 2], [Ship-to Address], [Ship-to Address 2],
          [Ship-to City], [Ship-to Contact], [Order Date], [Posting Date], [Shipment Date], [Posting Description],
          [Location Code], [Currency Code], [Salesperson Code], [Ship-to Post Code], [Ship-to County],
          [Ship-to Country_Region Code], [External Document No_], [User ID]
          from [".$this->config->item('sqlserver_live')."Sales Shipment Header] where [Posting Date] between '".$from."' and '".$to."';";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-10-17
    function get_sls_shipment_line_from_nav($from,$to){
        $db = $this->load->database('sql_server_live', true);

        $query_temp = "select [Document No_], [Line No_], [Sell-to Customer No_], [type], [no_], [Location Code], [Posting Group],
          [Shipment Date], [description], [Unit of Measure], [quantity], [Unit Price], [Unit Cost], [Gross Weight], [Net Weight],
          [Order No_], [Order Line No_], [Quantity Invoiced], [Item Category Code], [Product Group Code], [Item Charge Base Amount],
          [Bin Code], [Posting Date], [VAT Base Amount]
          from [".$this->config->item('sqlserver_live')."Sales Shipment Line] where [Posting Date] between '".$from."' and '".$to."' and [Quantity]>0;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-10-17
    function insert_sls_shipment_header_to_local($result){
        foreach($result as $row){
          $data = array(
           "no" => $row["no_"]
           ,"sell_to_cust_no" => $row["Sell-to Customer No_"]
           ,"bill_to_cust_no" => $row["Bill-to Customer No_"]
           ,"sell_to_name" => $row["Sell-to Customer Name"]
           ,"sell_to_name2" => $row["Sell-to Customer Name 2"]
           ,"bill_to_addr" => $row["Bill-to Address"]
           ,"bill_to_addr2" => $row["Bill-to Address 2"]
           ,"bill_to_city" => $row["Bill-to City"]
           ,"bill_to_contact" => $row["Bill-to Contact"]
           ,"ship_to_code" => $row["Ship-to Code"]
           ,"ship_to_name" => $row["Ship-to Name"]
           ,"ship_to_name2" => $row["Ship-to Name 2"]
           ,"ship_to_addr" => $row["Ship-to Address"]
           ,"ship_to_addr2" => $row["Ship-to Address 2"]
           ,"ship_to_city" => $row["Ship-to City"]
           ,"ship_to_contact" => $row["Ship-to Contact"]
           ,"order_date" => $row["Order Date"]
           ,"posting_date" => $row["Posting Date"]
           ,"shipment_date" => $row["Shipment Date"]
           ,"posting_description" => $row["Posting Description"]
           ,"location_code" => $row["Location Code"]
           ,"currency_code" => $row["Currency Code"]
           ,"sales_person_code" => $row["Salesperson Code"]
           ,"ship_to_post_code" => $row["Ship-to Post Code"]
           ,"ship_to_county" => $row["Ship-to County"]
           ,"ship_to_country_code" => $row["Ship-to Country_Region Code"]
           ,"external_document_no" => $row["External Document No_"]
           ,"userr" => $row["User ID"]
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('sls_shipment_header', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
        }
    }
    //---

    // 2023-10-17
    function insert_sls_shipment_line_to_local($result){
        foreach($result as $row){
          $data = array(
           "document_no" => $row["Document No_"]
           ,"line_no" => $row["Line No_"]
           ,"sell_to_cust_no" => $row["Sell-to Customer No_"]
           ,"typee" => $row["type"]
           ,"no" => $row["no_"]
           ,"location_code" => $row["Location Code"]
           ,"posting_group" => $row["Posting Group"]
           ,"shipment_date" => $row["Shipment Date"]
           ,"description" => $row["description"]
           ,"uom" => $row["Unit of Measure"]
           ,"qty" => $row["quantity"]
           ,"unit_price" => $row["Unit Price"]
           ,"unit_cost" => $row["Unit Cost"]
           ,"gross_weight" => $row["Gross Weight"]
           ,"net_weight" => $row["Net Weight"]
           ,"order_no" => $row["Order No_"]
           ,"order_line_no" => $row["Order Line No_"]
           ,"qty_invoiced" => $row["Quantity Invoiced"]
           ,"item_cat_codee" => $row["Item Category Code"]
           ,"product_group_codee" => $row["Product Group Code"]
           ,"item_charge_base_amount" => $row["Item Charge Base Amount"]
           ,"bin_code" => $row["Bin Code"]
           ,"posting_date" => $row["Posting Date"]
           ,"vat_base_amount" => $row["VAT Base Amount"]
          );

          $db = $this->load->database('default2', true);
          $insert_query = $this->db->insert_string('sls_shipment_line', $data);
          $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
          $result = $db->query($insert_query);
        }
    }
    //---
}



















?>
