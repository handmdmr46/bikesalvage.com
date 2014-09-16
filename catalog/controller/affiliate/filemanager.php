<?php
class ControllerAffiliateFileManager extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('affiliate/filemanager');

		$this->data['title'] = $this->language->get('heading_title');

		$this->data['template_url'] = 'catalog/view/theme/' . $this->config->get('config_template');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['entry_folder'] = $this->language->get('entry_folder');
		$this->data['entry_move'] = $this->language->get('entry_move');
		$this->data['entry_copy'] = $this->language->get('entry_copy');
		$this->data['entry_rename'] = $this->language->get('entry_rename');

		$this->data['button_folder'] = $this->language->get('button_folder');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_move'] = $this->language->get('button_move');
		$this->data['button_copy'] = $this->language->get('button_copy');
		$this->data['button_rename'] = $this->language->get('button_rename');
		$this->data['button_upload'] = $this->language->get('button_upload');
		$this->data['button_refresh'] = $this->language->get('button_refresh');
		$this->data['button_submit'] = $this->language->get('button_submit');

		$this->data['error_select'] = $this->language->get('error_select');
		$this->data['error_directory'] = $this->language->get('error_directory');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['directory'] = HTTPS_SERVER . 'image/affiliate/';
		} else {
			$this->data['directory'] = HTTP_SERVER . 'image/affiliate/';
		}

		// define('DIR_IMAGE', '/Applications/MAMP/htdocs/open-cart1.6/image/');
		$directory = rtrim(DIR_IMAGE . 'affiliate');

		if (!is_dir($directory)) {
			mkdir($directory, 0777);
		}

		$this->data['affiliate_dir'] = strtolower($this->affiliate->getLastname() . '_' . $this->affiliate->getId());

		if (!is_dir($directory . '/' . $this->data['affiliate_dir'])) {
			mkdir($directory . '/' . str_replace('../', '', $this->data['affiliate_dir']), 0777);
		}

		$this->load->model('tool/image');

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		if (isset($this->request->get['field'])) {
			$this->data['field'] = $this->request->get['field'];
		} else {
			$this->data['field'] = '';
		}

		if (isset($this->request->get['CKEditorFuncNum'])) {
			$this->data['fckeditor'] = $this->request->get['CKEditorFuncNum'];
		} else {
			$this->data['fckeditor'] = false;
		}

		$this->template = $this->config->get('config_template') . '/template/affiliate/filemanager.tpl';

		$this->response->setOutput($this->render());
	}

	/* ___________________FUNCTION REFERENCE___________________
	*
	*	DIR_IMAGE                                -- '/Applications/MAMP/htdocs/open-cart/image/' --
	*	glob(pattern,flags)                      -- The glob() function returns an array of filenames or directories matching a specified pattern -- GLOB_ONLYDIR returns only directories which match the pattern --
	*	str_replace(find,replace,string,count)   -- The str_replace() function replaces some characters with some other characters in a string --
	*	rtrim(string,charlist)                   -- The rtrim() function removes whitespace or other predefined characters from the right side of a string --
	*	basename(path,suffix)                    -- The basename() function returns the filename from a path --
	*	str_replace(find,replace,string,count)   -- find and replace a string --
	*	mkdir(path,mode,recursive,context)       -- this uses mkdir(path,mode) --
	*	is_dir(file)                             -- The is_dir() function checks whether the specified file is a directory --
	*   strlen(string)                           -- The strlen() function returns the length of a string --
	*
	*/

	public function image() {
		$this->load->model('tool/image');

		$this->data['affiliate_dir'] = strtolower($this->affiliate->getLastname() . '_' . $this->affiliate->getId());

		if (isset($this->request->get['image'])) {
			$this->response->setOutput($this->model_tool_image->resize(html_entity_decode($this->request->get['image'], ENT_QUOTES, 'UTF-8'), 100, 100));
		}
	}

	// gets directory folder to start in @ left-column -- on start --
	public function directory() {
		$json = array();

		$this->data['affiliate_dir'] = strtolower($this->affiliate->getLastname() . '_' . $this->affiliate->getId());

		if (isset($this->request->post['directory'])) {

			// what folder to start in?
			$directories = glob(rtrim(DIR_IMAGE . 'affiliate/' . $this->data['affiliate_dir'] . '/' . str_replace('../', '', $this->request->post['directory']) , '/') . '/*', GLOB_ONLYDIR);

			if ($directories) {
				$i = 0;

				foreach ($directories as $directory) {

					$json[$i]['data'] = basename($directory);

					$json[$i]['attributes']['directory'] = utf8_substr($directory, strlen(DIR_IMAGE . 'affiliate/' . $this->data['affiliate_dir'] . '/' . $this->request->post['directory']));

					$children = glob(rtrim($directory, '/') . '/*', GLOB_ONLYDIR);

					if ($children)  {
						$json[$i]['children'] = ' ';
					}

					$i++;
				}
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	// gets image files from the directory, displays thumbnails in right-column
	public function files() {
		$json = array();

		$this->data['affiliate_dir'] = strtolower($this->affiliate->getLastname() . '_' . $this->affiliate->getId());

		// gets files from 'affiliate/' directory folder
		if (!empty($this->request->post['directory'])) {
			$directory = DIR_IMAGE . 'affiliate/' . $this->data['affiliate_dir'] . '/' . str_replace('../', '', $this->request->post['directory']);
		} else {
			$directory = DIR_IMAGE . 'affiliate/' . $this->data['affiliate_dir'] . '/';
		}

		$allowed = array(
			'.jpg',
			'.jpeg',
			'.png',
			'.gif'
		);

		$files = glob(rtrim($directory, '/') . '/*');

		if ($files) {
			foreach ($files as $file) {
				if (is_file($file)) {
					$ext = strrchr($file, '.');
				} else {
					$ext = '';
				}

				if (in_array(strtolower($ext), $allowed)) {
					$size = filesize($file);

					$i = 0;

					$suffix = array(
						'B',
						'KB',
						'MB',
						'GB',
						'TB',
						'PB',
						'EB',
						'ZB',
						'YB'
					);

					while (($size / 1024) > 1) {
						$size = $size / 1024;
						$i++;
					}

					$json[] = array(
						'filename' => basename($file),
						'file'     => utf8_substr($file, utf8_strlen(DIR_IMAGE . 'affiliate/')),
						'size'     => round(utf8_substr($size, 0, utf8_strpos($size, '.') + 4), 2) . $suffix[$i]
					);
				}
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	// creates new directory folder
	public function create() {
		$this->language->load('affiliate/filemanager');

		$this->data['affiliate_dir'] = strtolower($this->affiliate->getLastname() . '_' . $this->affiliate->getId());

		$json = array();

		if (isset($this->request->post['directory'])) {
			if (isset($this->request->post['name']) || $this->request->post['name']) {
				$directory = rtrim(DIR_IMAGE . 'affiliate/' . $this->data['affiliate_dir'] . '/' . str_replace('../', '', $this->request->post['directory']), '/');

				if (!is_dir($directory)) {
					$json['error'] = $this->language->get('error_directory');
				}

				if (file_exists($directory . '/' . str_replace('../', '', $this->request->post['name']))) {
					$json['error'] = $this->language->get('error_exists');
				}
			} else {
				$json['error'] = $this->language->get('error_name');
			}
		} else {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!isset($json['error'])) {
			mkdir($directory . '/' . str_replace('../', '', $this->request->post['name']), 0777);

			$json['success'] = $this->language->get('text_create');
		}

		$this->response->setOutput(json_encode($json));
	}

	// deletes selected directory folder
	public function delete() {
		$this->language->load('affiliate/filemanager');

		$this->data['affiliate_dir'] = strtolower($this->affiliate->getLastname() . '_' . $this->affiliate->getId());

		$json = array();

		if (isset($this->request->post['path'])) {
			$path = rtrim(DIR_IMAGE . 'affiliate/' . $this->data['affiliate_dir'] . '/' . str_replace('../', '', html_entity_decode($this->request->post['path'], ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($path)) {
				$json['error'] = $this->language->get('error_select');
			}

			if ($path == rtrim(DIR_IMAGE . 'affiliate/' . $this->data['affiliate_dir'] . '/', '/')) {
				$json['error'] = $this->language->get('error_delete');
			}
		} else {
			$json['error'] = $this->language->get('error_select');
		}

		if (!isset($json['error'])) {
			if (is_file($path)) {
				unlink($path);
			} elseif (is_dir($path)) {
				$files = array();

				$path = array($path . '*');

				while(count($path) != 0) {
					$next = array_shift($path);

					foreach(glob($next) as $file) {
						if (is_dir($file)) {
							$path[] = $file . '/*';
						}

						$files[] = $file;
					}
				}

				rsort($files);

				foreach ($files as $file) {
					if (is_file($file)) {
						unlink($file);
					} elseif(is_dir($file)) {
						rmdir($file);
					}
				}
			}

			$json['success'] = $this->language->get('text_delete');
		}

		$this->response->setOutput(json_encode($json));
	}

	// moves selected image to new directory folder
	public function move() {
		$this->language->load('affiliate/filemanager');

		$this->data['affiliate_dir'] = strtolower($this->affiliate->getLastname() . '_' . $this->affiliate->getId());

		$json = array();

		if (isset($this->request->post['from']) && isset($this->request->post['to'])) {
			$from = rtrim(DIR_IMAGE . 'affiliate/' . str_replace('../', '', html_entity_decode($this->request->post['from'], ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($from)) {
				$json['error'] = $this->language->get('error_missing');
			}

			if ($from == DIR_IMAGE . 'data') {
				$json['error'] = $this->language->get('error_default');
			}

			$to = rtrim(DIR_IMAGE . 'affiliate/' . $this->data['affiliate_dir'] . '/' . str_replace('../', '', html_entity_decode($this->request->post['to'], ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($to)) {
				$json['error'] = $this->language->get('error_move');
			}

			if (file_exists($to . '/' . basename($from))) {
				$json['error'] = $this->language->get('error_exists');
			}
		} else {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!isset($json['error'])) {
			rename($from, $to . '/' . basename($from));

			$json['success'] = $this->language->get('text_move');
		}

		$this->response->setOutput(json_encode($json));
	}

	// copy image to new directory folder
	public function copy() {
		$this->language->load('affiliate/filemanager');

		$this->data['affiliate_dir'] = strtolower($this->affiliate->getLastname() . '_' . $this->affiliate->getId());

		$json = array();

		if (isset($this->request->post['path']) && isset($this->request->post['name'])) {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
				$json['error'] = $this->language->get('error_filename');
			}

			$old_name = rtrim(DIR_IMAGE . 'affiliate/' . $this->data['affiliate_dir'] . '/' . str_replace('../', '', html_entity_decode($this->request->post['path'], ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($old_name) || $old_name == DIR_IMAGE . 'data') {
				$json['error'] = $this->language->get('error_copy');
			}

			if (is_file($old_name)) {
				$ext = strrchr($old_name, '.');
			} else {
				$ext = '';
			}

			$new_name = dirname($old_name) . '/' . str_replace('../', '', html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8') . $ext);

			if (file_exists($new_name)) {
				$json['error'] = $this->language->get('error_exists');
			}
		} else {
			$json['error'] = $this->language->get('error_select');
		}

		if (!isset($json['error'])) {
			if (is_file($old_name)) {
				copy($old_name, $new_name);
			} else {
				$this->recursiveCopy($old_name, $new_name);
			}

			$json['success'] = $this->language->get('text_copy');
		}

		$this->response->setOutput(json_encode($json));
	}

	// recursive copy image to new directory folder
	function recursiveCopy($source, $destination) {
		$directory = opendir($source);

		$this->data['affiliate_dir'] = strtolower($this->affiliate->getLastname() . '_' . $this->affiliate->getId());

		@mkdir($destination);

		while (false !== ($file = readdir($directory))) {
			if (($file != '.') && ($file != '..')) {
				if (is_dir($source . '/' . $file)) {
					$this->recursiveCopy($source . '/' . $file, $destination . '/' . $file);
				} else {
					copy($source . '/' . $file, $destination . '/' . $file);
				}
			}
		}

		closedir($directory);
	}

	// sends output to protected function recursiveFolders()
	public function folders() {
		$this->data['affiliate_dir'] = strtolower($this->affiliate->getLastname() . '_' . $this->affiliate->getId());

		$this->response->setOutput($this->recursiveFolders(DIR_IMAGE . 'affiliate/' . $this->data['affiliate_dir'] . '/'));

	}

	// displays directory folders in select list -- example -- move() function
	protected function recursiveFolders($directory) {
		$output = '';

		$this->data['affiliate_dir'] = strtolower($this->affiliate->getLastname() . '_' . $this->affiliate->getId());

		$output .= '<option value="' . utf8_substr($directory, strlen(DIR_IMAGE . 'affiliate/' . $this->data['affiliate_dir'] . '/')) . '">' . utf8_substr($directory, strlen(DIR_IMAGE . 'affiliate/'. $this->data['affiliate_dir'] . '/')) . '</option>';

		$directories = glob(rtrim(str_replace('../', '', $directory), '/') . '/*', GLOB_ONLYDIR);

		foreach ($directories  as $directory) {
			$output .= $this->recursiveFolders($directory);
		}

		return $output;
	}

	// rename the selected image
	public function rename() {
		$this->language->load('affiliate/filemanager');

		$this->data['affiliate_dir'] = strtolower($this->affiliate->getLastname() . '_' . $this->affiliate->getId());

		$json = array();

		if (isset($this->request->post['path']) && isset($this->request->post['name'])) {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
				$json['error'] = $this->language->get('error_filename');
			}

			$old_name = rtrim(DIR_IMAGE . 'affiliate/' . str_replace('../', '', html_entity_decode($this->request->post['path'], ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($old_name) || $old_name == DIR_IMAGE . 'data') {
				$json['error'] = $this->language->get('error_rename');
			}

			if (is_file($old_name)) {
				$ext = strrchr($old_name, '.');
			} else {
				$ext = '';
			}

			$new_name = dirname($old_name) . '/' . str_replace('../', '', html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8') . $ext);

			if (file_exists($new_name)) {
				$json['error'] = $this->language->get('error_exists');
			}
		}

		if (!isset($json['error'])) {
			rename($old_name, $new_name);

			$json['success'] = $this->language->get('text_rename');
		}

		$this->response->setOutput(json_encode($json));
	}

	// gets image from your computer and uploads to the web server
	public function upload() {
		$this->language->load('affiliate/filemanager');

		$this->data['affiliate_dir'] = strtolower($this->affiliate->getLastname() . '_' . $this->affiliate->getId());

		$json = array();

		if (isset($this->request->post['directory'])) {
			if (isset($this->request->files['image']) && $this->request->files['image']['tmp_name']) {
				$filename = basename(html_entity_decode($this->request->files['image']['name'], ENT_QUOTES, 'UTF-8'));

				if ((strlen($filename) < 3) || (strlen($filename) > 255)) {
					$json['error'] = $this->language->get('error_filename');
				}

				// upload directory
				$directory = rtrim(DIR_IMAGE . 'affiliate/' . $this->data['affiliate_dir'] . '/' . str_replace('../', '', $this->request->post['directory']), '/');

				if (!is_dir($directory)) {
					$json['error'] = $this->language->get('error_directory');
				}

				if ($this->request->files['image']['size'] > 300000) {
					$json['error'] = $this->language->get('error_file_size');
				}

				$allowed = array(
					'image/jpeg',
					'image/pjpeg',
					'image/png',
					'image/x-png',
					'image/gif',
					'application/x-shockwave-flash'
				);

				if (!in_array($this->request->files['image']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_file_type');
				}

				$allowed = array(
					'.jpg',
					'.jpeg',
					'.gif',
					'.png',
					'.flv'
				);

				if (!in_array(strtolower(strrchr($filename, '.')), $allowed)) {
					$json['error'] = $this->language->get('error_file_type');
				}

				if ($this->request->files['image']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = 'error_upload_' . $this->request->files['image']['error'];
				}
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		} else {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!isset($json['error'])) {
			if (@move_uploaded_file($this->request->files['image']['tmp_name'], $directory . '/' . $filename)) {
				$json['success'] = $this->language->get('text_uploaded');
			} else {
				$json['error'] = $this->language->get('error_uploaded');
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>