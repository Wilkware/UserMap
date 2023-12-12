<?php

declare(strict_types=1);

// Allgemeine Funktionen
require_once __DIR__ . '/../libs/_traits.php';

/**
 * CLASS UserMap
 */
class UserMap extends IPSModule
{
    use DebugHelper;
    use ProfileHelper;
    use VariableHelper;

    // ModulID (Location Control)
    private const LOCATION_CONTROL_GUID = '{45E97A63-F870-408A-B259-2933F7EABF74}';
    // WP REST API
    private const WP_REST_URL = 'https://wilkware.de/wp-json/usermap/symcon';
    private const WP_REST_APW = 'YXBwLnVzZXI6YXZDUSByMDNPIEU1Q1AgMUJFeiBOUDVKIGxtUVY=';

    /**
     * Overrides the internal IPSModule::Create($id) function
     */
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        // User ID
        $this->RegisterAttributeInteger('UserID', 0);
        // Name (username) variable
        $this->RegisterPropertyString('Name', '');
        // Coordinates (LAT, LON)
        $this->RegisterPropertyString('Coords', '{"latitude":0,"longitude":0}');
        // Link List
        $this->RegisterPropertyString('Links', '[]');
    }

    /**
     * Overrides the internal IPSModule::Destroy($id) function
     */
    public function Destroy()
    {
        //Never delete this line!
        parent::Destroy();
    }

    /**
     * Configuration Form.
     *
     * @return JSON configuration string.
     */
    public function GetConfigurationForm()
    {
        // Get Form
        $form = json_decode(file_get_contents(__DIR__ . '/form.json'), true);

        $uid = $this->ReadAttributeInteger('UserID');
        $name = $this->ReadPropertyString('Name');
        $coords = json_decode($this->ReadPropertyString('Coords'), true);
        // Buttons
        if ($uid != 0) {
            // Update
            if (($name != '') && ($coords['latitude'] != 0) && ($coords['longitude'] != 0)) {
                $form['actions'][1]['items'][1]['enabled'] = true;
            }
            // Delete
            $form['actions'][1]['items'][2]['enabled'] = true;
        } else {
            if (($name != '') && ($coords['latitude'] != 0) && ($coords['longitude'] != 0)) {
                $form['actions'][1]['items'][0]['enabled'] = true;
            }
        }
        //$this->SendDebug(__FUNCTION__, $form);
        return json_encode($form);
    }

    /**
     * Overrides the internal IPSModule::ApplyChanges($id) function
     */
    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();

        $name = $this->ReadPropertyString('Name');
        $coords = json_decode($this->ReadPropertyString('Coords'), true);
        $this->SendDebug(__FUNCTION__, 'Name: ' . $name . ', Lat:' . $coords['latitude'] . ', Lon:' . $coords['longitude']);
        if (($name == '') || ($coords['latitude'] == 0) || ($coords['longitude'] == 0)) {
            $this->SetStatus(201);
        } else {
            $this->SetStatus(102);
        }
    }

    /**
     * RequestAction.
     *
     *  @param string $ident Ident.
     *  @param string $value Value.
     */
    public function RequestAction($ident, $value)
    {
        // Debug output
        $this->SendDebug(__FUNCTION__, $ident . ' => ' . $value);
        switch ($ident) {
            case 'map':
                $this->Map($value);
                break;
            case 'copy':
                $this->Copy($value);
                break;
            default:
                // ERROR!!!
                break;
        }
        return true;
    }

    /**
     * Reset my registered marker
     *
     * @param int $id new User/Post ID
     */
    public function ResetMyMarker(int $uid)
    {
        $old = $this->ReadAttributeInteger('UserID');
        if ($uid >= 0) {
            $this->WriteAttributeInteger('UserID', $uid);
        }
        return $old;
    }

    /**
     * Hide/unhide form buttons.
     *
     */
    private function ToggleButtons()
    {
        $uid = $this->ReadAttributeInteger('UserID');
        $this->UpdateFormField('btnRegister', 'enabled', ($uid == 0));
        $this->UpdateFormField('btnUpdate', 'enabled', ($uid != 0));
        $this->UpdateFormField('btnDelete', 'enabled', ($uid != 0));
    }

    /**
     * Register, update or delete user map infos.
     *
     * @param string $value False for transition otherwise true
     */
    private function Map(string $value)
    {
        $this->SendDebug(__FUNCTION__, $value);
        // check instance state
        if ($this->GetStatus() != 102) {
            $this->SendDebug(__FUNCTION__, 'Status: Instance is not active.');
            return;
        }
        // prepeare header
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . self::WP_REST_APW,
        ];
        // prepeare body
        $name = $this->ReadPropertyString('Name');
        $coords = json_decode($this->ReadPropertyString('Coords'), true);
        $table = json_decode($this->ReadPropertyString('Links'), true);
        $links = [];
        foreach ($table as $row) {
            $links[$row['Type']] = $row['Link'];
        }
        $body = [];
        $body['name'] = $name;
        $body['coords'] = [$coords['latitude'], $coords['longitude']];
        if (!empty($links)) {
            $body['links'] = $links;
        }
        $request = json_encode($body);
        // Action
        $id = $this->ReadAttributeInteger('UserID');
        $url = self::WP_REST_URL . '?' . $value . '=' . $id;
        //$this->SendDebug(__FUNCTION__, 'Request: ' . $url);
        $response = $this->Request($url, $headers, $request);
        $text = 'Error when calling the function!';
        if ($response !== false) {
            $data = json_decode($response, true);
            if (isset($data['ID'])) {
                // Register successful?
                if (($value == 'register') && ($data['ID'] != 0)) {
                    $this->WriteAttributeInteger('UserID', $data['ID']);
                    $text = 'Function successfully executed!';
                }
                elseif (($value == 'delete') && ($data['ID'] == 0)) {
                    $this->WriteAttributeInteger('UserID', $data['ID']);
                    $text = 'Function successfully executed!';
                }
                elseif (($value == 'update') && ($data['ID'] != 0)) {
                    $text = 'Function successfully executed!';
                }
            }
        }
        // Update Buttons
        $this->ToggleButtons();
        // Echo message
        $this->EchoMessage($text);
    }

    /**
     * Copy the location data from the system in the form.
     *
     * @param bool $value No usage
     */
    private function Copy(bool $value)
    {
        $this->SendDebug(__FUNCTION__, $value);
        $location = $this->GetLocationData();
        $this->SendDebug(__FUNCTION__, $location);
        if (!empty($location)) {
            $this->UpdateFormField('Coords', 'value', $location);
        }
        else {
            $this->EchoMessage('No location data available!');
        }
    }

    /**
     * Returns the users location data stored in symcon.
     *
     * @return array location data
     */
    private function GetLocationData()
    {
        $ids = IPS_GetInstanceListByModuleID(self::LOCATION_CONTROL_GUID);
        foreach ($ids as $id) {
            // always the first
            return IPS_GetProperty($id, 'Location');
        }
        return '';
    }

    /*
     * Request - Sends the request to the device
     *
     * If $request not null, we will send a POST request, else a GET request.
     * Over the $method parameter can we force a POST or GET request!
     *
     * @param string $url Url to call
     * @param array $header Header information
     * @param string $request Request data
     * @param string $mehtod 'GET' od 'POST'
     * @return mixed response data or false.
     */
    private function Request(string $url, array $headers, ?string $request, string $method = 'GET')
    {
        //$this->SendDebug(__FUNCTION__, $url, 0);
        //$this->SendDebug(__FUNCTION__, $headers, 0);
        // prepeare curl call
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($request != null) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        } else {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');

        if (!$response = curl_exec($curl)) {
            $error = sprintf('Request failed for URL: %s - Error: %s', $url, curl_error($curl));
            $this->SendDebug(__FUNCTION__, $error, 0);
        }
        curl_close($curl);
        $this->SendDebug(__FUNCTION__, $response, 0);
        return $response;
    }

    /**
     * Show message via popup
     *
     * @param string $caption echo message
     */
    private function EchoMessage(string $caption)
    {
        $this->UpdateFormField('EchoMessage', 'caption', $this->Translate($caption));
        $this->UpdateFormField('EchoPopup', 'visible', true);
    }
}