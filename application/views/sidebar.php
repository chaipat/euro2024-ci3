    <aside class="sidebar">
        <?php 
        $this->load->view('widgets-rectangle1');

        if(isset($widgets_team)){
			$this->load->view('widgets-team', $widgets_team);
		}

        if(isset($widgets_topscores)){
			$this->load->view('widgets-topscores', $widgets_topscores);
		}

        if(isset($widgets_program)){
			$this->load->view('widgets-program', $widgets_program);
		}

        $this->load->view('widgets-facebook');
        $this->load->view('widgets-rectangle2');

        if(isset($widgets_result)){
			$this->load->view('widgets-result', $widgets_result);
		}
        ?>
    </aside>