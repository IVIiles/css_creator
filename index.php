<?php
declare(strict_types=1);

/**
 * Classe principale pour la génération de CSS interactif
 * Conforme PSR-12 avec typage strict et sécurité renforcée
 */
class CssCreator
{
    // Configuration centrale (SSOT - Single Source of Truth)
    private const PROJECT_NAME = 'css_creator';
    private const DEFAULT_CSS_FILENAME = 'generated_styles.css';
    private const ALLOWED_ELEMENTS = ['button', 'input', 'card', 'navbar', 'footer', 'header', 'sidebar'];
    private const COLOR_PALETTE = ['#3a86ff', '#fb5607', '#8338ec', '#ff006e', '#ffbe0b'];
    
    // Propriétés typées strictement
    private string $css_creator_output_dir;
    private array $css_creator_elements = [];
    private string $css_creator_css_code = '';
    
    /**
     * Constructeur avec injection de configuration
     */
    public function __construct(string $output_dir = 'downloads')
    {
        $this->css_creator_output_dir = rtrim($output_dir, '/');
        $this->initializeCss();
    }
    
    /**
     * Initialise le code CSS de base
     */
    private function initializeCss(): void
    {
        $this->css_creator_css_code = <<<CSS
/* CSS généré par CSS Creator - $(date('Y-m-d H:i:s')) */
:root {
    --primary-color: #3a86ff;
    --secondary-color: #fb5607;
    --accent-color: #8338ec;
    --text-color: #333333;
    --bg-color: #f8f9fa;
    --card-bg: #ffffff;
    --border-radius: 8px;
    --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

/* Reset de base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    line-height: 1.6;
    color: var(--text-color);
    background-color: var(--bg-color);
}

CSS;
    }
    
    /**
     * Ajoute un élément avec son CSS
     */
    public function addElement(string $element_type, array $properties = []): bool
    {
        try {
            if (!in_array($element_type, self::ALLOWED_ELEMENTS, true)) {
                throw new InvalidArgumentException("Type d'élément non autorisé: {$element_type}");
            }
            
            $element_id = $this->css_creator_generate_element_id($element_type);
            $css_rules = $this->css_creator_generate_css_for_element($element_type, $element_id, $properties);
            
            $this->css_creator_elements[] = [
                'id' => $element_id,
                'type' => $element_type,
                'properties' => $properties,
                'css' => $css_rules
            ];
            
            $this->css_creator_css_code .= "\n" . $css_rules;
            
            return true;
            
        } catch (Exception $e) {
            error_log("Erreur lors de l'ajout d'élément: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Génère un ID unique pour un élément
     */
    private function css_creator_generate_element_id(string $element_type): string
    {
        $timestamp = (string) microtime(true);
        $random = (string) rand(1000, 9999);
        return self::PROJECT_NAME . '_' . $element_type . '_' . md5($timestamp . $random);
    }
    
    /**
     * Génère le CSS pour un type d'élément spécifique
     */
    private function css_creator_generate_css_for_element(string $type, string $id, array $properties): string
    {
        $css_map = [
            'button' => $this->css_creator_generate_button_css($id, $properties),
            'input' => $this->css_creator_generate_input_css($id, $properties),
            'card' => $this->css_creator_generate_card_css($id, $properties),
            'navbar' => $this->css_creator_generate_navbar_css($id, $properties),
            'footer' => $this->css_creator_generate_footer_css($id, $properties),
            'header' => $this->css_creator_generate_header_css($id, $properties),
            'sidebar' => $this->css_creator_generate_sidebar_css($id, $properties)
        ];
        
        return $css_map[$type] ?? "/* Type d'élément non pris en charge: {$type} */";
    }
    
    /**
     * Génère le CSS pour un bouton
     */
    private function css_creator_generate_button_css(string $id, array $properties): string
    {
        $bg_color = $properties['bg_color'] ?? self::COLOR_PALETTE[0];
        $text_color = $properties['text_color'] ?? '#ffffff';
        
        return <<<CSS
/* Bouton: {$id} */
#{$id} {
    display: inline-block;
    padding: 12px 24px;
    background-color: {$bg_color};
    color: {$text_color};
    border: none;
    border-radius: var(--border-radius);
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    text-align: center;
    text-decoration: none;
    box-shadow: var(--box-shadow);
}

#{$id}:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

#{$id}:active {
    transform: translateY(0);
}

#{$id}:focus {
    outline: 2px solid {$bg_color}80;
    outline-offset: 2px;
}

CSS;
    }
    
    /**
     * Génère le CSS pour un champ de saisie
     */
    private function css_creator_generate_input_css(string $id, array $properties): string
    {
        $border_color = $properties['border_color'] ?? '#ddd';
        
        return <<<CSS
/* Champ de saisie: {$id} */
#{$id} {
    width: 100%;
    max-width: 300px;
    padding: 10px 15px;
    border: 2px solid {$border_color};
    border-radius: var(--border-radius);
    font-size: 16px;
    transition: var(--transition);
    background-color: white;
}

#{$id}:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px var(--primary-color)20;
    outline: none;
}

#{$id}::placeholder {
    color: #999;
}

CSS;
    }
    
    /**
     * Génère le CSS pour une carte
     */
    private function css_creator_generate_card_css(string $id, array $properties): string
    {
        return <<<CSS
/* Carte: {$id} */
#{$id} {
    background-color: var(--card-bg);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 24px;
    margin: 16px 0;
    transition: var(--transition);
}

#{$id}:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

#{$id} .card-header {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 16px;
    color: var(--text-color);
}

#{$id} .card-body {
    color: #666;
    line-height: 1.8;
}

#{$id} .card-footer {
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #eee;
}

CSS;
    }
    
    /**
     * Génère le CSS pour une navbar
     */
    private function css_creator_generate_navbar_css(string $id, array $properties): string
    {
        return <<<CSS
/* Navigation: {$id} */
#{$id} {
    background-color: var(--card-bg);
    box-shadow: var(--box-shadow);
    padding: 0 24px;
    position: sticky;
    top: 0;
    z-index: 1000;
}

#{$id} .navbar-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 16px 0;
}

#{$id} .navbar-brand {
    font-size: 24px;
    font-weight: 700;
    color: var(--primary-color);
    text-decoration: none;
}

#{$id} .navbar-menu {
    display: flex;
    list-style: none;
    gap: 24px;
}

#{$id} .navbar-menu a {
    text-decoration: none;
    color: var(--text-color);
    font-weight: 500;
    transition: var(--transition);
    padding: 8px 0;
}

#{$id} .navbar-menu a:hover {
    color: var(--primary-color);
}

CSS;
    }
    
    /**
     * Génère le CSS pour un footer
     */
    private function css_creator_generate_footer_css(string $id, array $properties): string
    {
        return <<<CSS
/* Footer: {$id} */
#{$id} {
    background-color: #2c3e50;
    color: #ecf0f1;
    padding: 40px 0;
    margin-top: 60px;
}

#{$id} .footer-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
}

#{$id} .footer-section h3 {
    color: white;
    margin-bottom: 20px;
    font-size: 20px;
}

#{$id} .footer-section p {
    line-height: 1.8;
    opacity: 0.9;
}

#{$id} .footer-bottom {
    text-align: center;
    padding-top: 20px;
    margin-top: 40px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    opacity: 0.7;
}

CSS;
    }
    
    /**
     * Génère le CSS pour un header
     */
    private function css_creator_generate_header_css(string $id, array $properties): string
    {
        return <<<CSS
/* Header: {$id} */
#{$id} {
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    color: white;
    padding: 80px 0;
    text-align: center;
}

#{$id} .header-content {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 24px;
}

#{$id} h1 {
    font-size: 48px;
    margin-bottom: 24px;
    font-weight: 800;
}

#{$id} .subtitle {
    font-size: 20px;
    opacity: 0.9;
    margin-bottom: 40px;
}

CSS;
    }
    
    /**
     * Génère le CSS pour une sidebar
     */
    private function css_creator_generate_sidebar_css(string $id, array $properties): string
    {
        return <<<CSS
/* Sidebar: {$id} */
#{$id} {
    background-color: var(--card-bg);
    width: 280px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    box-shadow: var(--box-shadow);
    transition: var(--transition);
    z-index: 100;
}

#{$id} .sidebar-header {
    padding: 24px;
    border-bottom: 1px solid #eee;
    text-align: center;
}

#{$id} .sidebar-menu {
    list-style: none;
    padding: 0;
}

#{$id} .sidebar-menu li {
    border-bottom: 1px solid #f5f5f5;
}

#{$id} .sidebar-menu a {
    display: block;
    padding: 16px 24px;
    color: var(--text-color);
    text-decoration: none;
    transition: var(--transition);
}

#{$id} .sidebar-menu a:hover {
    background-color: #f8f9fa;
    padding-left: 28px;
    color: var(--primary-color);
}

CSS;
    }
    
    /**
     * Récupère le code CSS généré
     */
    public function getCssCode(): string
    {
        return htmlspecialchars($this->css_creator_css_code, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Sauvegarde le CSS dans un fichier
     */
    public function saveToFile(): string
    {
        try {
            if (!is_dir($this->css_creator_output_dir)) {
                mkdir($this->css_creator_output_dir, 0755, true);
            }
            
            $file_path = $this->css_creator_output_dir . '/' . self::DEFAULT_CSS_FILENAME;
            $bytes_written = file_put_contents($file_path, $this->css_creator_css_code);
            
            if ($bytes_written === false) {
                throw new RuntimeException("Impossible d'écrire dans le fichier: {$file_path}");
            }
            
            return $file_path;
            
        } catch (Exception $e) {
            error_log("Erreur de sauvegarde: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Génère le code HTML pour l'interface
     */
    public function generateHtmlInterface(): string
    {
        $elements_list = '';
        foreach (self::ALLOWED_ELEMENTS as $element) {
            $elements_list .= '<button class="css_creator_element_item" data-type="' . htmlspecialchars($element) . '">' 
                           . ucfirst($element) . '</button>';
        }
        
        $html = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Creator - Générateur de CSS interactif</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Styles de base générés dynamiquement */
        {$this->css_creator_css_code}
        
        /* Styles de l'interface CSS Creator */
        .css_creator_interface {
            --sidebar-width: 280px;
            --sidebar-collapsed: 60px;
            --primary: #3a86ff;
            --secondary: #fb5607;
            --dark: #2d3748;
            --light: #f7fafc;
            --gray: #e2e8f0;
            --transition-speed: 0.3s;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--light);
            color: var(--dark);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .css_creator_container {
            display: flex;
            min-height: 100vh;
            transition: margin-left var(--transition-speed), margin-right var(--transition-speed);
        }
        
        /* Sidebar gauche */
        .css_creator_sidebar_left {
            width: var(--sidebar-width);
            background-color: white;
            border-right: 1px solid var(--gray);
            transition: width var(--transition-speed);
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }
        
        .css_creator_sidebar_left.collapsed {
            width: var(--sidebar-collapsed);
        }
        
        .css_creator_sidebar_header {
            padding: 20px;
            border-bottom: 1px solid var(--gray);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .css_creator_sidebar_title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            white-space: nowrap;
            overflow: hidden;
        }
        
        .css_creator_toggle_btn {
            background: none;
            border: none;
            color: var(--dark);
            cursor: pointer;
            font-size: 20px;
            padding: 5px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        
        .css_creator_toggle_btn:hover {
            background-color: var(--gray);
        }
        
        .css_creator_elements_container {
            padding: 20px;
        }
        
        .css_creator_element_item {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 10px;
            background-color: white;
            border: 2px solid var(--gray);
            border-radius: 8px;
            cursor: grab;
            font-weight: 600;
            color: var(--dark);
            transition: all 0.2s;
            text-align: left;
        }
        
        .css_creator_element_item:hover {
            border-color: var(--primary);
            background-color: #f0f7ff;
            transform: translateX(5px);
        }
        
        .css_creator_element_item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Zone principale */
        .css_creator_main {
            flex: 1;
            margin-left: var(--sidebar-width);
            margin-right: var(--sidebar-width);
            padding: 20px;
            transition: margin-left var(--transition-speed), margin-right var(--transition-speed);
            min-height: 100vh;
        }
        
        .css_creator_main.full-width {
            margin-left: var(--sidebar-collapsed);
            margin-right: var(--sidebar-collapsed);
        }
        
        .css_creator_header {
            background-color: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .css_creator_title {
            font-size: 28px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .css_creator_subtitle {
            color: #718096;
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .css_creator_workspace {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            min-height: 500px;
            border: 2px dashed var(--gray);
            transition: border-color 0.3s;
            margin-bottom: 30px;
        }
        
        .css_creator_workspace.drag-over {
            border-color: var(--primary);
            background-color: #f0f7ff;
        }
        
        .css_creator_elements_grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .css_creator_element_preview {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid var(--gray);
            transition: transform 0.2s;
        }
        
        .css_creator_element_preview:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        
        .css_creator_element_actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--gray);
        }
        
        /* Sidebar droite */
        .css_creator_sidebar_right {
            width: var(--sidebar-width);
            background-color: #1a202c;
            color: white;
            border-left: 1px solid #2d3748;
            transition: width var(--transition-speed);
            position: fixed;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            overflow-y: auto;
        }
        
        .css_creator_sidebar_right.collapsed {
            width: var(--sidebar-collapsed);
        }
        
        .css_creator_code_header {
            padding: 20px;
            border-bottom: 1px solid #2d3748;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .css_creator_code_title {
            font-size: 18px;
            font-weight: 700;
            color: #63b3ed;
            white-space: nowrap;
            overflow: hidden;
        }
        
        .css_creator_code_container {
            padding: 20px;
        }
        
        .css_creator_code_block {
            background-color: #2d3748;
            border-radius: 8px;
            padding: 20px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.5;
            color: #e2e8f0;
            white-space: pre-wrap;
            word-wrap: break-word;
            max-height: 600px;
            overflow-y: auto;
            margin-bottom: 20px;
            border: 1px solid #4a5568;
        }
        
        .css_creator_actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .css_creator_btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .css_creator_btn_primary {
            background-color: var(--primary);
            color: white;
        }
        
        .css_creator_btn_primary:hover {
            background-color: #2b6cb0;
            transform: translateY(-2px);
        }
        
        .css_creator_btn_secondary {
            background-color: #4a5568;
            color: white;
        }
        
        .css_creator_btn_secondary:hover {
            background-color: #2d3748;
            transform: translateY(-2px);
        }
        
        .css_creator_btn_success {
            background-color: #38a169;
            color: white;
        }
        
        .css_creator_btn_success:hover {
            background-color: #2f855a;
            transform: translateY(-2px);
        }
        
        /* Responsive - Mobile First */
        @media (max-width: 1024px) {
            .css_creator_container {
                flex-direction: column;
            }
            
            .css_creator_sidebar_left,
            .css_creator_sidebar_right {
                position: relative;
                width: 100%;
                height: auto;
                max-height: 300px;
            }
            
            .css_creator_sidebar_left.collapsed,
            .css_creator_sidebar_right.collapsed {
                width: 100%;
                height: 60px;
                overflow: hidden;
            }
            
            .css_creator_main {
                margin: 0;
                order: 2;
            }
            
            .css_creator_elements_grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .css_creator_sidebar_header,
            .css_creator_code_header {
                padding: 15px;
            }
            
            .css_creator_main {
                padding: 15px;
            }
            
            .css_creator_workspace {
                padding: 20px;
            }
            
            .css_creator_title {
                font-size: 24px;
            }
            
            .css_creator_actions {
                flex-direction: column;
            }
        }
        
        @media (max-width: 480px) {
            .css_creator_header {
                padding: 20px;
            }
            
            .css_creator_title {
                font-size: 22px;
            }
        }
        
        /* Animation pour les nouveaux éléments */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .css_creator_element_preview {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Notification */
        .css_creator_notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #38a169;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            transform: translateY(100px);
            opacity: 0;
            transition: transform 0.3s, opacity 0.3s;
        }
        
        .css_creator_notification.show {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="css_creator_interface">
        <div class="css_creator_container">
            <!-- Sidebar gauche - Éléments UI -->
            <aside class="css_creator_sidebar_left" id="css_creator_sidebar_left">
                <div class="css_creator_sidebar_header">
                    <h2 class="css_creator_sidebar_title">
                        <i class="fas fa-shapes"></i> <span class="sidebar-text">Éléments UI</span>
                    </h2>
                    <button class="css_creator_toggle_btn" id="css_creator_toggle_left">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
                <div class="css_creator_elements_container">
                    <p style="color: #718096; margin-bottom: 15px; font-size: 14px;">
                        <i class="fas fa-mouse-pointer"></i> 
                        <span class="sidebar-text">Glissez les éléments dans la zone de travail</span>
                    </p>
                    {$elements_list}
                </div>
            </aside>
            
            <!-- Zone principale -->
            <main class="css_creator_main" id="css_creator_main">
                <header class="css_creator_header">
                    <h1 class="css_creator_title">CSS Creator</h1>
                    <p class="css_creator_subtitle">
                        Créez votre CSS interactivement. Glissez des éléments, personnalisez-les et copiez le code CSS généré.
                    </p>
                    <div class="css_creator_actions">
                        <button class="css_creator_btn css_creator_btn_success" id="css_creator_reset">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </button>
                        <button class="css_creator_btn css_creator_btn_primary" id="css_creator_add_sample">
                            <i class="fas fa-plus"></i> Ajouter un exemple
                        </button>
                    </div>
                </header>
                
                <section class="css_creator_workspace" id="css_creator_workspace">
                    <h3 style="margin-bottom: 20px; color: #4a5568;">
                        <i class="fas fa-paint-brush"></i> Zone de travail
                    </h3>
                    <p style="color: #718096; margin-bottom: 20px;">
                        Glissez des éléments depuis la barre de gauche ici. Vous pouvez réorganiser les éléments par glisser-déposer.
                    </p>
                    <div class="css_creator_elements_grid" id="css_creator_elements_grid">
                        <!-- Les éléments ajoutés apparaîtront ici -->
                    </div>
                </section>
                
                <footer style="text-align: center; color: #718096; padding: 20px; font-size: 14px;">
                    <p>
                        <i class="fas fa-code"></i> CSS Creator - Généré le {$this->css_creator_get_current_date()}
                    </p>
                </footer>
            </main>
            
            <!-- Sidebar droite - Code CSS -->
            <aside class="css_creator_sidebar_right" id="css_creator_sidebar_right">
                <div class="css_creator_code_header">
                    <h2 class="css_creator_code_title">
                        <i class="fas fa-code"></i> <span class="sidebar-text">CSS Généré</span>
                    </h2>
                    <button class="css_creator_toggle_btn" id="css_creator_toggle_right">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="css_creator_code_container">
                    <div class="css_creator_code_block" id="css_creator_code_display">
{$this->getCssCode()}
                    </div>
                    <div class="css_creator_actions">
                        <button class="css_creator_btn css_creator_btn_primary" id="css_creator_copy">
                            <i class="fas fa-copy"></i> Copier CSS
                        </button>
                        <button class="css_creator_btn css_creator_btn_secondary" id="css_creator_download">
                            <i class="fas fa-download"></i> Télécharger
                        </button>
                    </div>
                </div>
            </aside>
        </div>
        
        <!-- Notification -->
        <div class="css_creator_notification" id="css_creator_notification">
            <span id="css_creator_notification_text">CSS copié dans le presse-papier !</span>
        </div>
    </div>

    <script>
        // Variables globales de l'application
        const CSS_CREATOR = {
            elements: [],
            draggedElement: null,
            isLeftSidebarCollapsed: false,
            isRightSidebarCollapsed: false
        };
        
        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des boutons de toggle des sidebars
            document.getElementById('css_creator_toggle_left').addEventListener('click', function() {
                toggleSidebar('left');
            });
            
            document.getElementById('css_creator_toggle_right').addEventListener('click', function() {
                toggleSidebar('right');
            });
            
            // Gestion du glisser-déposer
            setupDragAndDrop();
            
            // Bouton de réinitialisation
            document.getElementById('css_creator_reset').addEventListener('click', function() {
                if (confirm('Voulez-vous vraiment réinitialiser tous les éléments ?')) {
                    resetWorkspace();
                }
            });
            
            // Bouton d'ajout d'exemple
            document.getElementById('css_creator_add_sample').addEventListener('click', function() {
                addSampleElements();
            });
            
            // Bouton de copie CSS
            document.getElementById('css_creator_copy').addEventListener('click', function() {
                copyCssToClipboard();
            });
            
            // Bouton de téléchargement
            document.getElementById('css_creator_download').addEventListener('click', function() {
                downloadCssFile();
            });
            
            // Charger les éléments depuis le localStorage si disponibles
            loadFromLocalStorage();
        });
        
        // Fonction pour basculer une sidebar
        function toggleSidebar(side) {
            const main = document.getElementById('css_creator_main');
            const sidebar = document.getElementById(`css_creator_sidebar_${side}`);
            const toggleBtn = document.getElementById(`css_creator_toggle_${side}`);
            const icon = toggleBtn.querySelector('i');
            
            if (side === 'left') {
                CSS_CREATOR.isLeftSidebarCollapsed = !CSS_CREATOR.isLeftSidebarCollapsed;
                sidebar.classList.toggle('collapsed');
                icon.classList.toggle('fa-chevron-left');
                icon.classList.toggle('fa-chevron-right');
                
                // Masquer le texte dans la sidebar réduite
                document.querySelectorAll('.sidebar-text').forEach(text => {
                    if (CSS_CREATOR.isLeftSidebarCollapsed) {
                        text.style.display = 'none';
                    } else {
                        text.style.display = 'inline';
                    }
                });
            } else {
                CSS_CREATOR.isRightSidebarCollapsed = !CSS_CREATOR.isRightSidebarCollapsed;
                sidebar.classList.toggle('collapsed');
                icon.classList.toggle('fa-chevron-right');
                icon.classList.toggle('fa-chevron-left');
            }
            
            // Ajuster la largeur principale
            if (CSS_CREATOR.isLeftSidebarCollapsed && CSS_CREATOR.isRightSidebarCollapsed) {
                main.classList.add('full-width');
            } else {
                main.classList.remove('full-width');
            }
        }
        
        // Configuration du glisser-déposer
        function setupDragAndDrop() {
            const workspace = document.getElementById('css_creator_workspace');
            const elementItems = document.querySelectorAll('.css_creator_element_item');
            
            // Événements pour les éléments draggables
            elementItems.forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    CSS_CREATOR.draggedElement = this.getAttribute('data-type');
                    this.classList.add('dragging');
                    e.dataTransfer.setData('text/plain', this.getAttribute('data-type'));
                    
                    // Feedback visuel
                    workspace.classList.add('drag-over');
                });
                
                item.addEventListener('dragend', function() {
                    this.classList.remove('dragging');
                    workspace.classList.remove('drag-over');
                });
            });
            
            // Événements pour la zone de dépôt
            workspace.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('drag-over');
            });
            
            workspace.addEventListener('dragleave', function() {
                this.classList.remove('drag-over');
            });
            
            workspace.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');
                
                const elementType = e.dataTransfer.getData('text/plain');
                if (elementType) {
                    addElementToWorkspace(elementType);
                }
            });
        }
        
        // Ajoute un élément à la zone de travail
        function addElementToWorkspace(type) {
            const elementId = 'css_creator_element_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
            const grid = document.getElementById('css_creator_elements_grid');
            
            // Créer l'élément HTML
            let elementHtml = '';
            let elementLabel = '';
            
            switch(type) {
                case 'button':
                    elementHtml = '<button id="' + elementId + '">Bouton exemple</button>';
                    elementLabel = 'Bouton';
                    break;
                case 'input':
                    elementHtml = '<input type="text" id="' + elementId + '" placeholder="Entrez votre texte">';
                    elementLabel = 'Champ de saisie';
                    break;
                case 'card':
                    elementHtml = '<div id="' + elementId + '"><div class="card-header">Titre de la carte</div><div class="card-body">Contenu de la carte avec du texte d\'exemple.</div></div>';
                    elementLabel = 'Carte';
                    break;
                case 'navbar':
                    elementHtml = '<nav id="' + elementId + '"><div class="navbar-container"><a href="#" class="navbar-brand">Logo</a><ul class="navbar-menu"><li><a href="#">Accueil</a></li><li><a href="#">Services</a></li><li><a href="#">Contact</a></li></ul></div></nav>';
                    elementLabel = 'Navigation';
                    break;
                case 'footer':
                    elementHtml = '<footer id="' + elementId + '"><div class="footer-content"><div class="footer-section"><h3>À propos</h3><p>Description de votre entreprise ou projet.</p></div></div><div class="footer-bottom"><p>&copy; ' + new Date().getFullYear() + ' - Tous droits réservés</p></div></footer>';
                    elementLabel = 'Pied de page';
                    break;
                case 'header':
                    elementHtml = '<header id="' + elementId + '"><div class="header-content"><h1>Titre principal</h1><p class="subtitle">Sous-titre descriptif pour votre page</p></div></header>';
                    elementLabel = 'En-tête';
                    break;
                case 'sidebar':
                    elementHtml = '<aside id="' + elementId + '"><div class="sidebar-header"><h3>Menu</h3></div><ul class="sidebar-menu"><li><a href="#">Tableau de bord</a></li><li><a href="#">Profil</a></li><li><a href="#">Paramètres</a></li></ul></aside>';
                    elementLabel = 'Barre latérale';
                    break;
            }
            
            // Créer le conteneur de prévisualisation
            const previewElement = document.createElement('div');
            previewElement.className = 'css_creator_element_preview';
            previewElement.innerHTML = `
                <div style="margin-bottom: 10px; font-weight: 600; color: #4a5568;">
                    <i class="fas fa-${getIconForType(type)}"></i> ${elementLabel}
                </div>
                <div style="margin: 15px 0;">
                    ${elementHtml}
                </div>
                <div class="css_creator_element_actions">
                    <button class="css_creator_toggle_btn" onclick="editElement('${elementId}')" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="css_creator_toggle_btn" onclick="removeElement('${elementId}', this)" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            
            grid.appendChild(previewElement);
            
            // Ajouter à la liste des éléments
            CSS_CREATOR.elements.push({
                id: elementId,
                type: type,
                timestamp: new Date().toISOString()
            });
            
            // Sauvegarder dans le localStorage
            saveToLocalStorage();
            
            // Mettre à jour l'affichage du CSS
            updateCssDisplay();
            
            // Afficher une notification
            showNotification('Élément ' + elementLabel + ' ajouté avec succès');
        }
        
        // Récupère l'icône pour un type d'élément
        function getIconForType(type) {
            const icons = {
                'button': 'mouse-pointer',
                'input': 'edit',
                'card': 'id-card',
                'navbar': 'bars',
                'footer': 'window-minimize',
                'header': 'heading',
                'sidebar': 'columns'
            };
            return icons[type] || 'cube';
        }
        
        // Supprime un élément
        function removeElement(elementId, button) {
            if (confirm('Supprimer cet élément ?')) {
                const previewElement = button.closest('.css_creator_element_preview');
                previewElement.remove();
                
                // Retirer de la liste
                CSS_CREATOR.elements = CSS_CREATOR.elements.filter(el => el.id !== elementId);
                
                // Sauvegarder dans le localStorage
                saveToLocalStorage();
                
                // Mettre à jour l'affichage du CSS
                updateCssDisplay();
                
                showNotification('Élément supprimé');
            }
        }
        
        // Édite un élément (fonctionnalité de base)
        function editElement(elementId) {
            showNotification('Fonctionnalité d\'édition à venir. Pour l\'instant, supprimez et recréez l\'élément.');
        }
        
        // Réinitialise l'espace de travail
        function resetWorkspace() {
            const grid = document.getElementById('css_creator_elements_grid');
            grid.innerHTML = '';
            CSS_CREATOR.elements = [];
            saveToLocalStorage();
            updateCssDisplay();
            showNotification('Espace de travail réinitialisé');
        }
        
        // Ajoute des éléments d'exemple
        function addSampleElements() {
            const sampleTypes = ['header', 'navbar', 'card', 'button', 'input', 'footer'];
            
            sampleTypes.forEach((type, index) => {
                setTimeout(() => {
                    addElementToWorkspace(type);
                }, index * 200);
            });
            
            showNotification('Exemples ajoutés');
        }
        
        // Met à jour l'affichage du CSS
        function updateCssDisplay() {
            // Dans une implémentation réelle, on ferait une requête AJAX au serveur
            // Pour cette démo, on simule avec un contenu statique
            const cssDisplay = document.getElementById('css_creator_code_display');
            const baseCss = `/* CSS généré par CSS Creator - ${new Date().toLocaleString()} */

:root {
    --primary-color: #3a86ff;
    --secondary-color: #fb5607;
    --accent-color: #8338ec;
    --text-color: #333333;
    --bg-color: #f8f9fa;
    --card-bg: #ffffff;
    --border-radius: 8px;
    --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    line-height: 1.6;
    color: var(--text-color);
    background-color: var(--bg-color);
}

/* Styles pour les éléments ajoutés */
${CSS_CREATOR.elements.map(el => generateCssForElement(el)).join('\n\n')}`;
            
            cssDisplay.textContent = baseCss;
        }
        
        // Génère le CSS pour un élément (simulé)
        function generateCssForElement(element) {
            const baseStyles = {
                'button': `button#${element.id} {
    display: inline-block;
    padding: 12px 24px;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius);
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}`,
                'input': `input#${element.id} {
    width: 100%;
    max-width: 300px;
    padding: 10px 15px;
    border: 2px solid #ddd;
    border-radius: var(--border-radius);
    font-size: 16px;
    transition: var(--transition);
}`,
                'card': `#${element.id} {
    background-color: var(--card-bg);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 24px;
    margin: 16px 0;
}`
            };
            
            return baseStyles[element.type] || `/* Style pour ${element.type} */\n#${element.id} {\n    /* Ajoutez vos styles ici */\n}`;
        }
        
        // Copie le CSS dans le presse-papier
        function copyCssToClipboard() {
            const cssText = document.getElementById('css_creator_code_display').textContent;
            
            navigator.clipboard.writeText(cssText).then(() => {
                showNotification('CSS copié dans le presse-papier !');
            }).catch(err => {
                console.error('Erreur de copie: ', err);
                showNotification('Erreur lors de la copie', 'error');
            });
        }
        
        // Télécharge le CSS en tant que fichier
        function downloadCssFile() {
            const cssText = document.getElementById('css_creator_code_display').textContent;
            const blob = new Blob([cssText], { type: 'text/css' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            
            a.href = url;
            a.download = 'styles_' + new Date().toISOString().split('T')[0] + '.css';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            showNotification('Fichier CSS téléchargé');
        }
        
        // Affiche une notification
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('css_creator_notification');
            const text = document.getElementById('css_creator_notification_text');
            
            text.textContent = message;
            
            if (type === 'error') {
                notification.style.backgroundColor = '#e53e3e';
            } else {
                notification.style.backgroundColor = '#38a169';
            }
            
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }
        
        // Sauvegarde dans le localStorage
        function saveToLocalStorage() {
            try {
                localStorage.setItem('css_creator_elements', JSON.stringify(CSS_CREATOR.elements));
            } catch (e) {
                console.error('Erreur de sauvegarde localStorage:', e);
            }
        }
        
        // Charge depuis le localStorage
        function loadFromLocalStorage() {
            try {
                const saved = localStorage.getItem('css_creator_elements');
                if (saved) {
                    const elements = JSON.parse(saved);
                    elements.forEach(el => {
                        // Recréer les éléments sauvegardés
                        setTimeout(() => {
                            addElementToWorkspace(el.type);
                        }, 100);
                    });
                }
            } catch (e) {
                console.error('Erreur de chargement localStorage:', e);
            }
        }
    </script>
</body>
</html>
HTML;

        return $html;
    }
    
    /**
     * Récupère la date actuelle formatée
     */
    private function css_creator_get_current_date(): string
    {
        return date('d/m/Y');
    }
}

// Point d'entrée de l'application
try {
    // Initialiser la classe
    $css_creator = new CssCreator();
    
    // Ajouter quelques éléments par défaut pour la démo
    $css_creator->addElement('button', ['bg_color' => '#3a86ff', 'text_color' => '#ffffff']);
    $css_creator->addElement('input', ['border_color' => '#ddd']);
    $css_creator->addElement('card', []);
    
    // Générer l'interface
    echo $css_creator->generateHtmlInterface();
    
} catch (Exception $e) {
    // Gestion d'erreur sécurisée
    header('Content-Type: text/html; charset=utf-8');
    http_response_code(500);
    
    $error_message = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    $error_trace = htmlspecialchars($e->getTraceAsString(), ENT_QUOTES, 'UTF-8');
    
    echo <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur - CSS Creator</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f8f9fa;
            color: #333;
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
        }
        .error-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-left: 5px solid #e53e3e;
        }
        h1 {
            color: #e53e3e;
            margin-bottom: 20px;
        }
        .error-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-family: monospace;
            font-size: 14px;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .back-btn {
            display: inline-block;
            background: #3a86ff;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>⚠️ Erreur dans CSS Creator</h1>
        <p>Une erreur s'est produite lors de l'exécution de l'application.</p>
        <div class="error-details">
            <strong>Message:</strong> {$error_message}
            
            <strong>Stack trace:</strong>
            {$error_trace}
        </div>
        <a href="javascript:location.reload()" class="back-btn">Réessayer</a>
    </div>
</body>
</html>
HTML;
    exit;
}