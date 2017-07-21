<?php

class LevelAccountController extends BaseController {
    
    public function showGetLevelAccounts()
	{
        $limit      = 3;
        $all_sites  = DB::table('SITE')->get();
        
        foreach($all_sites as $site) {
            $site->level_accounts = DB::table('LEVEL_ACCOUNT')
                ->where('site_id', $site->site_id)
                ->get();
                
            $site->opt_available    = array();
            $opt_used               = array();
            
            foreach ($site->level_accounts as $account) {
                $opt_used[]    = $account->level;
            }
            
            for ($idx = 0; 4 > $idx; $idx++) {
                $needle_pos = array_search($idx, $opt_used);
                
                if (FALSE !== $needle_pos) {
                    $site->opt_available[]    = $opt_used[$needle_pos];
                }
            }
        }
        
        return json_encode([$all_sites]);
	}
    
    public function addSaveLevelAccounts()
    {
        $lvl_acc_db     = new LevelAccount();
        $post_data      = Input::all();
        $dup_check      = 0;
        
        foreach ($post_data as $site) {
            
            $data['site_id']        = $site['site_id'];
            
            foreach ($site['level_accounts'] as $account) {
                
                $data['la_seq']         = $account['la_seq'];
                $data['level']          = $account['level'];
                $data['bank_name']      = $account['bank_name'];
                $data['bank_account']   = $account['bank_account'];
                $data['bank_owner']     = $account['bank_owner'];
                
                $lvl_acc_db->addUpdateRecord($data);
            }
        }
        
        $data = $this->showGetLevelAccounts();
        
        return $data;
    }
    
}
