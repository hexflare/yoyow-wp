# yoyow-wp
Yet another wordpress plugin for YOYOW blockchain

### Install
#### 1. Place yoyow-wp source code under {local wordpress path}/wp-content/plugins. 
#### 2. Download released zip file(https://github.com/hexflare/yoyow-wp/releases) and install from wordpress admin panel.

### Usage

#### 1. Activate YOYOW WP plugin
![active yoyow-wp plugin](https://github.com/hexflare/yoyow-wp/blob/main/active.png)


#### 2. Plugin settings
Following settings must be configured in priority to publish onchain post:
###### 1. RPC endpoint for YOYOW blockchain
###### 2. Chain ID
###### 3. A platform account
###### 4. A poster account which is authorized to the platform account and will be used as post author
###### 5. Secondary key of the platform account
###### 6. Onchain content type
![setting](https://github.com/hexflare/yoyow-wp/blob/main/setting.png)

###### As for "Onchain Content Type" setting, there are 4 types of content (Post URL, Original post, Hash256 and MD5 of post content) supported for YOYOW blockchain store.

#### 3. Publish onchain post
Select "onchain" option when publish/edit a post to upload preferred content to YOYOW blockchain. 
![onchain_post](https://github.com/hexflare/yoyow-wp/blob/main/onchain_post.png)
