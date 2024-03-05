<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'repository_inokufu', language 'en', branch 'MOODLE_40_STABLE'
 *
 * @package   repository_inokufu
 * @author    Inokufu, 2023
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once(__DIR__ . '/../../constants.php');

$string[CONFIG_PLUGIN] = "Configuration d'Inokufu Repository";
$string[PLUGIN_NAME] = 'Inokufu Search';
$string[PLUGIN_NAME_HELP] = 'De meilleures données pour un meilleur apprentissage ! Trouvez la bonne ressource au bon moment avec le plugin Inokufu Search.';
$string[PLUGIN_VIEW] = 'Voir le référentiel Inokufu Search';

$string[CONST_QUERY] = 'Rechercher une Ressource Pédagogique (en Français par défaut) :<br>Essayez quelque chose comme "Comment devenir charpentier"';
$string[CONST_TYPE] = 'Type :';
$string[CONST_PROVIDER] = 'Fournisseur :';
$string[CONST_LANG] = 'Langue :';
$string[CONST_NOT_FREE] = 'Inclure du contenu payant :';
$string[CONST_API_KEY] = 'Clé API';
$string[CONST_KEY_INFORMATION] = 'Obtenez votre <a href="https://gateway.inokufu.com/">clé API ici</a>.';

$string[CONST_OR] = 'OU';
$string[CONST_DEFAULT_LANG] = '- Langue par défaut -';
$string[CONST_ALL] = '- Tous -';

$string[CACHE_API_LO_NAME] = 'Cache pour l\'API LO';

$string[ERROR_TOO_MANY_REQUESTS] = 'Vous avez atteint la limite maximale de requêtes autorisées par minute pour notre service. Veuillez réessayer ultérieurement ou bien contactez l\'administrateur afin d\'augmenter votre quota.';
$string[ERROR_QUERY_FAILED] = 'Une erreur s\'est produite lors de votre requête API. Si le problème persiste, veuillez contacter votre administrateur.';
