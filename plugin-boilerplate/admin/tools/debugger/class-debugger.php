<?php

/**
 * The Debugger Tool
 *  - Output debug messages to js console
 *  - Log to file
 *
 * @package   PLUGIN_PACKAGE
 * @author    PLUGIN_AUTHOR_NAME <PLUGIN_AUTHOR_EMAIL>
 * @copyright COPYRIGHT
 * @license   PLUGIN_LICENSE_NAME
 * @link      PLUGIN_URI
 */

defined('ABSPATH') or die('No script kiddies please!');

namespace PLUGIN_PACKAGE\Admin\Tools;

class Debugger extends AbstractAdminTool
{
    protected $title = "Debugger";
    protected $slug = "debugger";
    protected $description = "Debug via console or log file.";


    public function render()
    {
        /** @var Gizmo_Logger $gizmo_logger */
        global $gizmo_logger;
        $redirect = false;
        if (isset($_GET["action"])) {
            switch ($_GET["action"]) {
                case "test_logging":
                    gizmo_log(array("test"=>"This is a test!"));
                    gizmo_log("This is another test!");
                    if($gizmo_logger->get_log_type()=="file"){
                        $redirect = true;
                    }
                    break;
                case "clear_file_log":
                    // Clear the log
                    file_put_contents($gizmo_logger->get_log_file_path(), "");
                    $redirect = true;
                    break;
                case "enable_logging":
                    $gizmo_logger->turn_on_logging();
                    $redirect = true;
                    break;
                case "disable_logging":
                    $gizmo_logger->turn_off_logging();
                    $redirect = true;
                    break;
                case "log_to_console":
                    $gizmo_logger->set_option("type", "console");
                    $redirect = true;
                    break;
                case "log_to_file":
                    $gizmo_logger->set_option("type", "file");
                    $redirect = true;
                    break;
                default:
                    break;
            }
        }

        if($redirect) {
            $query_params = array("page" => "taos-admin-debug");
            $redirection_url = admin_url('admin.php?' . http_build_query($query_params));
            require("templates/js-redirect.view.php");
        } else {
            require("templates/debug-log.view.php");
        }
    }

    private function redirect_after_action()
    {

        wp_redirect();
        exit;
    }
}
