<?php namespace App\Controllers;

require_once "vendor/autoload.php";

class UploadUsage extends \Connect\UsageAutomation
{

    public function processUsageForListing($listing)
    {
        //Detect concrete Provider Contract
        if($listing->contract->id === 'CRD-41560-05399-123') {
            //This is for Provider XYZ, also can be seen from $listing->provider->id and parametrized further via marketplace available at $listing->marketplace->id
            date_default_timezone_set('UTC'); //reporting must be always based on UTC
            $usages = [];
            //Creating QT SCHEMA records, pplease check Connect\Usage\FileUsageRecord for further possible data to be passed
            array_push($usages, new Connect\Usage\FileUsageRecord([
                'record_id' => 'unique record value',
                'item_search_criteria' => 'item.mpn', //Possible values are item.mpn or item.local_id
                'item_search_value' => 'SKUA', //Value defined as MPN on vendor portal
                'quantity' => 1, //Quantity to be reported
                'start_time_utc' => date('d-m-Y H:i:s', strtotime("-1 days")), //From when to report
                'end_time_utc' => date("Y-m-d H:i:s"), //Till when to report
                'asset_search_criteria' => 'parameter.param_b', //How to find the asset on Connect, typical use case is to use a parameter provided by vendor, in this case called param_b, additionally can be used asset.id in case you want to use Connect identifiers
                'asset_search_value' => 'tenant2'
            ]));
            $usageFile = new \Connect\Usage\File([
                "period" => [
                    "from"=> date('Y-m-d H:i:s', strtotime("-1 days")),
                    "to"=> date("Y-m-d H:i:s")
                ],
                'product' => new \Connect\Product(
                    ['id' => $listing->product->id]
                ),
                'contract' => new \Connect\Contract(
                    ['id' => $listing->contract->id]
                )
            ]);
            $this->usage->submitUsage($usageFile, $usages);
            return "processing done";
        }
        else{
            //Do Something different
        }
    }

}

//Main Code Block

try {

    $usageAutomation = new UploadUsage();
    $usageAutomation->process();

} catch (Exception $e) {
    print "Error processing usage for active listing requests:" . $e->getMessage();
}