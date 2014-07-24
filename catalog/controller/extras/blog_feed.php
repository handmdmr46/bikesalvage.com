<?php 
class ControllerExtrasBlogFeed extends Controller {
	public function index() {
		if ($this->config->get('blog_feed_status')) { 
			$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
			$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
            $output .= '<channel>';
			$output .= '<title>' . $this->config->get('config_name') . '</title>'; 
			$output .= '<description>' . $this->config->get('config_meta_description') . '</description>';
			$output .= '<link>' . HTTP_SERVER . '</link>';
			
			$this->load->model('extras/blog_category');
			
			$this->load->model('extras/blog');
			
			$this->load->model('tool/image');
			
			$blogs = $this->model_extras_blog->getBlogs();
			
			foreach ($blogs as $blog) {
				if ($blog['description']) {
					$output .= '<item>';
					$output .= '<title>' . $blog['title'] . '</title>';
					$output .= '<link>' . $this->url->link('extras/blog/getblog', 'blog_id=' . $blog['blog_id']) . '</link>';
					$output .= '<pubDate>' . date("d M Y", strtotime($blog['date_added'])) . '</pubDate>';
					$output .= '<description>' . $blog['description'] . '</description>';
					$output .= '<g:id>' . $blog['blog_id'] . '</g:id>';
					
					if ($blog['image']) {
						$output .= '<g:image_link>' . $this->model_tool_image->resize($blog['image'], 500, 500) . '</g:image_link>';
					} else {
						$output .= '<g:image_link>' . $this->model_tool_image->resize('no_image.jpg', 500, 500) . '</g:image_link>';
					}
					
					$output .= '</item>';
				}
			}
			
			$output .= '</channel>'; 
			$output .= '</rss>';	
			
			$this->response->addHeader('Content-Type: application/rss+xml');
			$this->response->setOutput($output);
		}
	}
}
?>