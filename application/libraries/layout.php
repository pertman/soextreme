<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{
    private $CI;
    private $var = array();

    /*
    |===============================================================================
    | Constructeur
    |===============================================================================
    */

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->var['output'] = '';

        //	Le titre est composé du nom de la méthode et du nom du contrôleur
        //	La fonction ucfirst permet d'ajouter une majuscule
        $this->var['titre'] = ucfirst($this->CI->router->fetch_method()) . ' - ' . ucfirst($this->CI->router->fetch_class());

        //	Nous initialisons la variable $charset avec la même valeur que
        //	la clé de configuration initialisée dans le fichier config.php
        $this->var['charset'] = $this->CI->config->item('charset');

        $this->var['css'] = array();
        $this->var['js'] = array();
    }

    /*
    |===============================================================================
    | Méthodes pour charger les vues
    |	. view
    |	. views
    |===============================================================================
    */

    public function view($name, $data = array())
    {
        $this->var['output'] .= $this->CI->load->view($name, $data, true);

        $this->CI->load->view('../default', $this->var);
    }

    public function views($name, $data = array())
    {
        $this->var['output'] .= $this->CI->load->view($name, $data, true);
        return $this;
    }



    /*
    |===============================================================================
    | Méthodes pour modifier les variables envoyées au layout
    |	. set_titre
    |	. set_charset
    |===============================================================================
    */
    public function set_titre($titre)
    {
        if(is_string($titre) AND !empty($titre))
        {
            $this->var['titre'] = $titre;
            return true;
        }
        return false;
    }

    public function set_charset($charset)
    {
        if(is_string($charset) AND !empty($charset))
        {
            $this->var['charset'] = $charset;
            return true;
        }
        return false;
    }



    /*
    |===============================================================================
    | Méthodes pour ajouter des feuilles de CSS et de JavaScript
    |	. ajouter_css
    |	. ajouter_js
    |===============================================================================
    */
    public function ajouter_css($nom)
    {
        if(is_string($nom) AND !empty($nom) AND file_exists('./assets/css/' . $nom . '.css'))
        {
            $this->var['css'][] = css_url($nom);
            return true;
        }
        return false;
    }

    public function ajouter_js($nom)
    {
        if(is_string($nom) AND !empty($nom) AND file_exists('./assets/js/' . $nom . '.js'))
        {
            $this->var['js'][] = js_url($nom);
            return true;
        }
        return false;
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
    <title><?php echo $titre; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
    <?php foreach($css as $url): ?>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $url; ?>" />
    <?php endforeach; ?>

</head>

<body>
<div id="contenu">
    <?php echo $output; ?>
</div>

<?php foreach($js as $url): ?>
    <script type="text/javascript" src="<?php echo $url; ?>"></script>
<?php endforeach; ?>

</body>

</html>
