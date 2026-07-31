<?php

declare(strict_types=1);

/** Generell funktions */
require_once __DIR__ . '/../libs/_traits.php';

/** Namespaced traits */
use Wilkware\UserMap\DebugHelper;
use Wilkware\UserMap\VariableHelper;

/**
 * Class UserMap
 */
class UserMap extends IPSModuleStrict
{
    // -------------------------------------------------------------------------
    // Traits
    // -------------------------------------------------------------------------

    use DebugHelper;
    use VariableHelper;

    // -------------------------------------------------------------------------
    // Constants
    // -------------------------------------------------------------------------

    /** @var string ModulID (Location Control)  */
    private const LOCATION_CONTROL_GUID = '{45E97A63-F870-408A-B259-2933F7EABF74}';

    /** @var string Wordpress REST API Url */
    private const WP_REST_URL = 'https://wilkware.de/wp-json/usermap/symcon';

    /** @var string Wordpress REST API Apw */
    private const WP_REST_APW = 'YXBwLnVzZXI6YXZDUSByMDNPIEU1Q1AgMUJFeiBOUDVKIGxtUVY=';

    // -------------------------------------------------------------------------
    // Methods
    // -------------------------------------------------------------------------

    /**
     * In contrast to Construct, this function is called only once when creating the instance and starting IP-Symcon.
     * Therefore, status variables and module properties which the module requires permanently should be created here.
     *
     * @return void
     */
    public function Create(): void
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

        // Set visualization type to 1, as we want to offer HTML
        $this->SetVisualizationType(1);
    }

    /**
     * This function is called when deleting the instance during operation and when updating via "Module Control".
     * The function is not called when exiting IP-Symcon.
     *
     * @return void
     */
    public function Destroy(): void
    {
        //Never delete this line!
        parent::Destroy();
    }

    /**
     * The content can be overwritten in order to transfer a self-created configuration page.
     * This way, content can be generated dynamically.
     * In this case, the "form.json" on the file system is completely ignored.
     *
     * @return string Content of the configuration page.
     */
    public function GetConfigurationForm(): string
    {
        // Get Form
        $form = json_decode(file_get_contents(__DIR__ . '/form.json'), true);

        // Extract Version
        $ins = IPS_GetInstance($this->InstanceID);
        $mod = IPS_GetModule($ins['ModuleInfo']['ModuleID']);
        $lib = IPS_GetLibrary($mod['LibraryID']);
        $form['actions'][2]['items'][2]['caption'] = sprintf('v%s.%d', $lib['Version'], $lib['Build']);

        $uid = $this->ReadAttributeInteger('UserID');
        $name = $this->ReadPropertyString('Name');
        $coords = json_decode($this->ReadPropertyString('Coords'), true);
        // Buttons
        if ($uid != 0) {
            // Update
            if (($name != '') && ($coords['latitude'] != 0) && ($coords['longitude'] != 0)) {
                $form['actions'][0]['items'][0]['items'][1]['enabled'] = true;
            }
            // Delete
            $form['actions'][0]['items'][0]['items'][2]['enabled'] = true;
        } else {
            if (($name != '') && ($coords['latitude'] != 0) && ($coords['longitude'] != 0)) {
                $form['actions'][0]['items'][0]['items'][0]['enabled'] = true;
            }
        }
        //$this->LogDebug(__FUNCTION__, $form);
        return json_encode($form);
    }

    /**
     * Is executed when "Apply" is pressed on the configuration page and immediately after the instance has been created.
     *
     * @return void
     */
    public function ApplyChanges(): void
    {
        //Never delete this line!
        parent::ApplyChanges();

        $name = $this->ReadPropertyString('Name');
        $coords = json_decode($this->ReadPropertyString('Coords'), true);
        $this->LogDebug(__FUNCTION__, 'Name: ' . $name . ', Lat:' . $coords['latitude'] . ', Lon:' . $coords['longitude']);
        if (($name == '') || ($coords['latitude'] == 0) || ($coords['longitude'] == 0)) {
            $this->SetStatus(201);
        } else {
            $this->SetStatus(102);
        }
    }

    /**
     * Is called when, for example, a button is clicked in the visualization.
     *
     * @param string $ident Ident of the variable
     * @param mixed $value The value to be set
     *
     * @return void
     */
    public function RequestAction(string $ident, mixed $value): void
    {
        // Debug output
        $this->LogDebug(__FUNCTION__, $ident . ' => ' . $value);
        switch ($ident) {
            case 'map':
                $this->Map($value);
                break;
            case 'copy':
                $this->Copy($value);
                break;
            default:
                $this->LogDebug(__FUNCTION__, 'There was no reaction to the action.');
                break;
        }

        // Send a complete update message to the display, as parameters may have changed
        // $this->UpdateVisualizationValue($this->GetFullUpdateMessage());
        return;
    }

    /**
     * Reset my registered marker
     *
     * @param int $uid new User/Post ID
     *
     * @return int Returns the previous ID
     */
    public function ResetMyMarker(int $uid): int
    {
        $oid = $this->ReadAttributeInteger('UserID');
        if ($uid >= 0) {
            $this->WriteAttributeInteger('UserID', $uid);
        }
        return $oid;
    }

    /**
     * If the HTML-SDK is to be used, this function must be overwritten in order to return the HTML content.
     *
     * @return string Initial display of a representation via HTML SDK
     */
    public function GetVisualizationTile(): string
    {
        // Add a script to set the values when loading, analogous to changes at runtime
        // Although the return from GetFullUpdateMessage is already JSON-encoded, json_encode is still executed a second time
        // This adds quotation marks to the string and any quotation marks within it are escaped correctly
        $handling = '<script>handleMessage(' . json_encode($this->GetFullUpdateMessage()) . ');</script>';
        // Add static HTML from file
        $module = file_get_contents(__DIR__ . '/module.html');
        // Important: $initialHandling at the end, as the handleMessage function is only defined in the HTML
        return $module . $handling;
    }

    /**
     * Hide/unhide form buttons.
     *
     * @return void
     */
    private function ToggleButtons(): void
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
     *
     * @return void
     */
    private function Map(string $value): void
    {
        $this->LogDebug(__FUNCTION__, $value);
        // check instance state
        if ($this->GetStatus() != 102) {
            $this->LogDebug(__FUNCTION__, 'Status: Instance is not active.');
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
        //$this->LogDebug(__FUNCTION__, 'Request: ' . $url);
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
     *
     * @return void
     */
    private function Copy(bool $value): void
    {
        $this->LogDebug(__FUNCTION__, $value);
        $location = $this->GetLocationData();
        $this->LogDebug(__FUNCTION__, $location);
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
     * @return string location data
     */
    private function GetLocationData(): string
    {
        $ids = IPS_GetInstanceListByModuleID(self::LOCATION_CONTROL_GUID);
        foreach ($ids as $id) {
            // always the first
            return IPS_GetProperty($id, 'Location');
        }
        return '';
    }

    /**
     * Sends the request to the device
     *
     * If $request not null, we will send a POST request, else a GET request.
     * Over the $method parameter can we force a POST or GET request!
     *
     * @param string $url Url to call
     * @param list<string> $headers Header information
     * @param string $request Request data
     * @param string $method 'GET' or 'POST'
     *
     * @return mixed response data or false.
     */
    private function Request(string $url, array $headers, ?string $request, string $method = 'GET')
    {
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
            $this->LogDebug(__FUNCTION__, $error);
        }
        curl_close($curl);
        $this->LogDebug(__FUNCTION__, $response);
        return $response;
    }

    /**
     * Generate a message that updates all elements in the HTML display.
     *
     * @return string JSON encoded message information
     */
    private function GetFullUpdateMessage(): string
    {
        // Fill resultset
        $result = [];
        $this->LogDebug(__FUNCTION__, $result);
        // send it
        return json_encode($result);
    }

    /**
     * Show message via popup
     *
     * @param string $caption echo message
     *
     * @return void
     */
    private function EchoMessage(string $caption): void
    {
        $this->UpdateFormField('EchoMessage', 'caption', $this->Translate($caption));
        $this->UpdateFormField('EchoPopup', 'visible', true);
    }
}