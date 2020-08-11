<?php

class nvi{

  private function call($action, $data){
    $url = 'https://tckimlik.nvi.gov.tr/Service/'.$action.'.asmx';
    $postData = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                 <soap12:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap12=\"http://www.w3.org/2003/05/soap-envelope\">
                  <soap12:Body>
                    $data
                  </soap12:Body>
                 </soap12:Envelope>";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/soap+xml; charset=utf-8']);
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpcode==200 ? trim(strip_tags($result))=='true' : false;
  }

  public function tcKimlikNoDogrula($tc, $ad, $soyad, $dogumyili){
    $data = "<TCKimlikNoDogrula xmlns=\"http://tckimlik.nvi.gov.tr/WS\">
              <TCKimlikNo>$tc</TCKimlikNo>
              <Ad>$ad</Ad>
              <Soyad>$soyad</Soyad>
              <DogumYili>$dogumyili</DogumYili>
             </TCKimlikNoDogrula>";
    $result = self::call('KPSPublic', $data);
    return $result!==false ? $result : false;
  }

  public function kisiVeCuzdanDogrula($tc, $tcSeri, $cuzdanNo, $cuzdanSeri, $ad, $soyad='', $dogumYil, $dogumAy='', $dogumGun=''){
    $data = "<KisiVeCuzdanDogrula xmlns=\"http://tckimlik.nvi.gov.tr/WS\">
              <TCKimlikNo>$tc</TCKimlikNo>
              <Ad>$ad</Ad>
              <Soyad>$soyad</Soyad>
              <SoyadYok>".(empty($soyad) ? 'true' : 'false')."</SoyadYok>
              <DogumGun>$dogumGun</DogumGun>
              <DogumGunYok>".(empty($dogumGun) ? 'true' : 'false')."</DogumGunYok>
              <DogumAy>$dogumAy</DogumAy>
              <DogumAyYok>".(empty($dogumAy) ? 'true' : 'false')."</DogumAyYok>
              <DogumYil>$dogumYil</DogumYil>
              <CuzdanSeri>$cuzdanSeri</CuzdanSeri>
              <CuzdanNo>$cuzdanNo</CuzdanNo>
              <TCKKSeriNo>$tcSeri</TCKKSeriNo>
            </KisiVeCuzdanDogrula>";
    $result = self::call('KPSPublicV2', $data);
    return $result!==false ? $result : false;
  }

  public function yabanciKimlikNoDogrula($kimlikNo, $ad, $soyad, $dogumGun, $dogumAy, $dogumYil){
    $data = "<YabanciKimlikNoDogrula xmlns=\"http://tckimlik.nvi.gov.tr/WS\">
              <KimlikNo>$kimlikNo</KimlikNo>
              <Ad>$ad</Ad>
              <Soyad>$soyad</Soyad>
              <DogumGun>$dogumGun</DogumGun>
              <DogumAy>$dogumAy</DogumAy>
              <DogumYil>$dogumYil</DogumYil>
             </YabanciKimlikNoDogrula>";
    $result = self::call('KPSPublicYabanciDogrula', $data);
    return $result!==false ? $result : false;
  }

}
