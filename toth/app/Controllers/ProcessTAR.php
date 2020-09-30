<?php
require_once "vendor/autoload.php";
class ProcessTAR extends \Connect\TierAccountRequestsAutomation
{
    public function processTierAccountRequest(\Connect\TierAccountRequest $request)
    {
        //$request is instance of \Connect\TierAccountRequest
        try{
            //Get changes
            $changes = $request->account->diffWithPreviousVersion();

            //Do something with external system to change TA data

            throw new \Connect\TierAccountRequestAccept("Proocessed");
        }
        catch (Exception $e){
            throw new \Connect\TierAccountRequestIgnore("Issue while processing, we ignore");
        }
    }

}

//Main Code Block

try{
    $tarProcessor = new ProcessTar();
    $tarProcessor->process();
} catch (Exception $e) {
    print "error ".$e->getMessage();
}