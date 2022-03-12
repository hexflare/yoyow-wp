<?php
/**
 * @package yoyow-wp
 * @version 1.0.0
 */
/*
Plugin Name: YOYOW WP
Plugin URI: https://github.com/hexflare/yoyow-wp
Description: Yet another wordpress plugin for YOYOW blockchain
Author: HexFlare
Version: 1.0.0
Author URI: https://github.com/hexflare
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html


{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/

use BitWasp\Bitcoin\Crypto\Hash;
use BitWasp\Buffertools\Buffer;
use YOYOW\Constant\OperationTypeId;
use YOYOW\Models\Operations\OperationType;
use YOYOW\Models\Operations\PostOperation;
use YOYOW\Models\Operations\Transaction;
use YOYOW\Utils\HttpClient;
use YOYOW\Utils\StringUtils;
use YOYOW\Utils\TransactionHelper;
use YOYOW\Utils\YOYOWApi;
use YOYOW\YOYOW;

require __DIR__ . "/vendor/autoload.php";

include 'Loader.php';
include 'Settings.php';
spl_autoload_register('Loader::autoload');
add_action('publish_post', 'post_onchain',1, 2);

function post_onchain($post_id, $post_obj) {
    if( post_onchain_check($post_id) or
        !$_POST['if_onchain'] or
        ! isset( $_POST['onchain_nonce'] ) or
        ! wp_verify_nonce( $_POST['onchain_nonce'], plugin_basename( __FILE__ ) ))
        return;

    $config = get_option('yoyow_plugin_config');
    $chain_id = $config["chain_id"];
    $rpc_url = $config["rpc_url"];
    $poster = $config["poster_id"];
    $platform = $config["platform_id"];
    $platform_secondary_key = $config["platform_secondary_key"];
    $content_type = $config["content_type"];

    if ( !$chain_id or !$rpc_url or !$poster or !$platform or !$platform_secondary_key or $content_type < 0 or $content_type >3)
        return;

    YOYOW::setChainId($chain_id);
    YOYOW::setEndPoint($rpc_url);
    HttpClient::getInstance()->setUrl(YOYOW::getEndPoint());

    $poster_full_stat = YOYOWApi::getFullAccountById($poster);

    $onchain_post_title = $post_obj->post_title;
    $onchain_post_id = $poster_full_stat->statistics->lastPostSequence + 1;
    switch($content_type){
        case 0:
            $onchain_post_content = get_permalink($post_id);
            break;
        case 1:
            $onchain_post_content = $post_obj->post_content;
            break;
        case 2:
            $onchain_post_content = Hash::sha256(Buffer::hex(StringUtils::String2Hex( $post_obj->post_content)))->getHex();
            break;
        case 3:
            $onchain_post_content = md5($post_obj->post_content);
            break;
        default:
            $onchain_post_content = '';
    }

    $onchain_hash_value = Hash::sha256(Buffer::hex(StringUtils::String2Hex($onchain_post_title . $onchain_post_content)))->getHex();
    $transaction = new Transaction();
    $postOperation = new PostOperation($onchain_post_id, $platform, $poster, $post_obj->post_title,
        $onchain_post_content, $onchain_hash_value, "{}");

    $operationType = new OperationType(OperationTypeId::getOperationIdFromClassName(get_class($postOperation)), $postOperation);
    $transaction->addOperationType($operationType);
    TransactionHelper::prepareTransaction($transaction);
    $transaction->generateSignature(YOYOW::getChainId(), $platform_secondary_key);
    $res = TransactionHelper::broadcast($transaction);
    if(!$res) add_post_meta($post_id, "onchain_post_id", $onchain_post_id,true);
}

add_action('add_meta_boxes', 'onchain_publish_option');

function post_onchain_check($post_id){
    return get_post_meta($post_id, "onchain_post_id", true);
}

function onchain_publish_option() {
    if(isset($_REQUEST['post']) && post_onchain_check($_REQUEST['post'])){
        return;
    }
    add_meta_box(
        'onchain_publish_inner_id',
        __( 'Publish Option' ),
        'onchain_publish_inner_option',
        'post',
        "normal"
    );
}

function onchain_publish_inner_option( $post ) {

    wp_nonce_field( plugin_basename( __FILE__ ), 'onchain_nonce' );
    if (!post_onchain_check($post->ID)) {
        echo '<label for="onchain_check" style="display: inline-block;height: 25px;line-height: 25px;">';
        _e("On Chain");
        echo '</label> ';
        echo '<input type="checkbox" id="if_onchain" name="if_onchain"  checked size="25" />';
        echo '<span id="onchain_notify"></span>';
    }
}

new Settings();

