<?php

namespace App\Helper;

use Google_Client;
use Google_Service_AnalyticsReporting;
use Google_Auth_AssertionCredentials;
use Google_Service_Analytics;
use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_Dimension;
use Google_Service_AnalyticsReporting_ReportRequest;
use Google_Service_AnalyticsReporting_GetReportsRequest;

class ApiAnalyticsHelper
{

    public function initializeAnalytics()
    {
      // Creates and returns the Analytics Reporting service object.
    
      // Load the Google API PHP Client Library.
      //require_once '../library/Api-Analytics/src/Google/autoload.php';
      require_once '../vendor/google/apiclient/src/Google/autoload.php';
    
      // Use the developers console and replace the values with your
      // service account email, and relative location of your key file.
      $SERVICE_ACCOUNT_EMAIL = "izibrickp12@izibrick.iam.gserviceaccount.com";
      $KEY_FILE_LOCATION = "../key/izibrick-58c974ddee46.p12";
    
      // Create and configure a new client object.
      $client = new Google_Client();
      $client->setApplicationName("Hello Analytics Reporting");
      $analytics = new Google_Service_AnalyticsReporting($client);
    
      // Read the generated client_secrets.p12 key.
      $key = file_get_contents($KEY_FILE_LOCATION);
      $cred = new Google_Auth_AssertionCredentials(
          $SERVICE_ACCOUNT_EMAIL,
          array(Google_Service_Analytics::ANALYTICS_READONLY),
          $key
      );
      $client->setAssertionCredentials($cred);
      if($client->getAuth()->isAccessTokenExpired()) {
        $client->getAuth()->refreshTokenWithAssertion($cred);
      }
    
      return $analytics;
    }
    
    function printResultsDate(&$reports) {
      for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
        $report = $reports[ $reportIndex ];
        $rows = $report->getData()->getRows();
    
        for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
          $row = $rows[ $rowIndex ];
          $metrics = $row->getMetrics();
          for ($i = 0; $i < count($metrics) ; $i++) {
            print($metrics[$i]['values'][0]."<br/>");
          }
        }
      }
    }    

    function getReportVisiteurUnique($analytics, $analyticsView) {
    
        // Replace with your view ID, for example XXXX.
        $VIEW_ID = $analyticsView;
        
        $dateJours = ApiAnalyticsHelper::getLatestDay(30);
        
        $mois = new Google_Service_AnalyticsReporting_DateRange();
        $mois->setStartDate(current($dateJours));
        $mois->setEndDate(end($dateJours));
        
        // Create the Metrics object.
        $sessions = new Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:newUsers");
        $sessions->setAlias("users");
        
        // Create the Metrics object.
        $dimensions = new Google_Service_AnalyticsReporting_Dimension();
        $dimensions->setName("ga:nthDay");
        
        // Create the ReportRequest object.
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDateRanges(array($mois));
        $request->setMetrics(array($sessions));
        $request->setDimensions(array($dimensions));
        
        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests( array( $request ));
        $reports = $analytics->reports->batchGet( $body );
        
        $visiteurs = '[';

        $i=0;
        for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
            $report = $reports[ $reportIndex ];
            $rows = $report->getData()->getRows();
            for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
                $row = $rows[ $rowIndex ];
                $metrics = $row->getMetrics();
                $dimension = $row->getDimensions();
                if($i == intval($dimension[0])) {
                    $visiteurs.= '[gd('.date("Y", strtotime($dateJours[$i])).','.date("m", strtotime($dateJours[$i])).','.date("d", strtotime($dateJours[$i])).'),'.$metrics[0]['values'][0].'],';
                }else{
                    while($i != intval($dimension[0])){
                        $visiteurs.= '[gd('.date("Y", strtotime($dateJours[$i])).','.date("m", strtotime($dateJours[$i])).','.date("d", strtotime($dateJours[$i])).'),0],';
                        $i++;   
                    }                    
                    $visiteurs.= '[gd('.date("Y", strtotime($dateJours[$i])).','.date("m", strtotime($dateJours[$i])).','.date("d", strtotime($dateJours[$i])).'),'.$metrics[0]['values'][0].'],';
                }
                $i++;
            }
        }
        $visiteurs.=']';
        return $visiteurs;      
    }
    
    function getReportVisiteurRecurrent($analytics, $analyticsView) {
    
        // Replace with your view ID, for example XXXX.
        $VIEW_ID = $analyticsView;
        
        $dateJours = ApiAnalyticsHelper::getLatestDay(30);
        
        $mois = new Google_Service_AnalyticsReporting_DateRange();
        $mois->setStartDate(current($dateJours));
        $mois->setEndDate(end($dateJours));
        
        // Create the Metrics object.
        $sessions = new Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:users");
        $sessions->setAlias("users");
        
        // Create the Metrics object.
        $dimensions = new Google_Service_AnalyticsReporting_Dimension();
        $dimensions->setName("ga:nthDay");
        
        // Create the ReportRequest object.
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDateRanges(array($mois));
        $request->setMetrics(array($sessions));
        $request->setDimensions(array($dimensions));
        
        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests( array( $request ));
        $reports = $analytics->reports->batchGet( $body );
        
        $visiteurs = '[';
        
        $i=0;
        for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
            $report = $reports[ $reportIndex ];
            $rows = $report->getData()->getRows();
            for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
                $row = $rows[ $rowIndex ];
                $metrics = $row->getMetrics();
                $dimension = $row->getDimensions();
                if($i == intval($dimension[0])) {
                    $visiteurs.= '[gd('.date("Y", strtotime($dateJours[$i])).','.date("m", strtotime($dateJours[$i])).','.date("d", strtotime($dateJours[$i])).'),'.$metrics[0]['values'][0].'],';
                }else{
                    while($i != intval($dimension[0])){
                        $visiteurs.= '[gd('.date("Y", strtotime($dateJours[$i])).','.date("m", strtotime($dateJours[$i])).','.date("d", strtotime($dateJours[$i])).'),0],';
                        $i++;   
                    }                    
                    $visiteurs.= '[gd('.date("Y", strtotime($dateJours[$i])).','.date("m", strtotime($dateJours[$i])).','.date("d", strtotime($dateJours[$i])).'),'.$metrics[0]['values'][0].'],';
                }
                $i++;
            }
        }
        $visiteurs.=']';
        return $visiteurs;      
    }
        
    public function getLatestDay($nbrJours){
        $dateJours = array();
        $date_courant = date("Y-m-d");
        $date_debut = date("Y-m-d", strtotime("-".$nbrJours." day", strtotime($date_courant)));        
        for($i = 0; $i < $nbrJours; $i++){
            $date_debut = date("Y-m-d", strtotime("+1 day", strtotime($date_debut)));
            $dateJours[$i] = $date_debut;          
        }
        return $dateJours;
    }

}
