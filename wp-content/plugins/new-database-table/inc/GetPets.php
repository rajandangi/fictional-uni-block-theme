<?php
class GetPets
{
    private $args;
    function __construct()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "pets";
        // $ourQuery = $wpdb->prepare("SELECT * FROM $table_name where species =%s LIMIT 100", array($_GET["species"]));
        $this->args = $this->getArgs();

        $query = "SELECT * FROM $table_name ";
        $countQuery = "SELECT COUNT(*) FROM $table_name ";

        $whereClause = $this->createWhereClause();

        if (!empty($whereClause)) {
            $query .= $whereClause;
            $countQuery .= $whereClause;

            $query .= " LIMIT 10";

            $this->count = $wpdb->get_var($wpdb->prepare($countQuery, array_values($this->args)));
            $this->pets = $wpdb->get_results($wpdb->prepare($query, array_values($this->args)));
        } else {
            $query .= " LIMIT 10";

            $this->count = $wpdb->get_var($countQuery);
            $this->pets = $wpdb->get_results($query);
        }
    }

    function getArgs()
    {
        $temp = [];

        if (isset($_GET['favcolor']))
            $temp['favcolor'] = sanitize_text_field($_GET['favcolor']);
        if (isset($_GET['species']))
            $temp['species'] = sanitize_text_field($_GET['species']);
        if (isset($_GET['minyear']))
            $temp['minyear'] = sanitize_text_field($_GET['minyear']);
        if (isset($_GET['maxyear']))
            $temp['maxyear'] = sanitize_text_field($_GET['maxyear']);
        if (isset($_GET['minweight']))
            $temp['minweight'] = sanitize_text_field($_GET['minweight']);
        if (isset($_GET['maxweight']))
            $temp['maxweight'] = sanitize_text_field($_GET['maxweight']);
        if (isset($_GET['favhobby']))
            $temp['favhobby'] = sanitize_text_field($_GET['favhobby']);
        if (isset($_GET['favfood']))
            $temp['favfood'] = sanitize_text_field($_GET['favfood']);

        return $temp;

    }

    function createWhereClause()
    {
        $whereQuery = "";
        $args = $this->args;

        if (!empty($args)) {
            $whereQuery = "WHERE ";
            $clauses = [];

            foreach ($args as $index => $item) {
                $clauses[] = $this->specificQuery($index);
            }

            $whereQuery .= implode(' AND ', $clauses);
        }
        return $whereQuery;
    }

    function specificQuery($index)
    {
        switch ($index) {
            case "minweight":
                return "petweight >= %d";
            case "maxweight":
                return "petweight <= %d";
            case "minyear":
                return "birthyear >= %d";
            case "maxyear":
                return "birthyear <= %d";
            default:
                return $index . " = %s";
        }
    }
}