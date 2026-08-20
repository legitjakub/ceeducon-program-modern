import fs from 'node:fs';
import path from 'node:path';

const root = path.resolve(import.meta.dirname, '..');
const out = path.join(root, 'wordpress-plugin/ceeducon-elementor-widgets/templates');
let sequence = 0;

const link = (url) => ({ url });
const media = (url, id = 0) => ({ id, url });
const widget = (type, settings = {}) => ({
  id: `cd${(++sequence).toString(16).padStart(6, '0')}`,
  elType: 'widget',
  widgetType: `ceeducon_${type.replaceAll('-', '_')}`,
  settings,
  elements: [],
});
const container = (...elements) => ({
  id: `cd${(++sequence).toString(16).padStart(6, '0')}`,
  elType: 'container',
  settings: {
    content_width: 'full',
    width: { unit: '%', size: 100, sizes: [] },
    padding: { unit: 'px', top: '0', right: '0', bottom: '0', left: '0', isLinked: true },
    gap: { unit: 'px', size: 0, sizes: [] },
  },
  elements,
});
const page = (title, blocks) => ({ version: '0.4', title, type: 'page', content: blocks.map((block) => container(block)) });

const themes = widget('themes');
const venue = (settings = {}) => widget('venue', settings);
const partners = (settings = {}) => widget('partners', settings);

const pages = {
  homepage: page('CEEDUCON Homepage', [
    widget('hero'),
    widget('text-section', { buttonText: 'More about CEEDUCON', buttonUrl: link('/about/') }),
    themes,
    widget('video'),
    widget('photo-gallery'),
    widget('schedule-overview'),
    widget('cards', {
      kicker: 'Plan ahead',
      title: 'Find the essentials quickly.',
      items: [
        { label: 'Practical', title: 'Getting to the conference', text: 'Venue, transport, accessibility and accommodation tips.', url: link('/practical-information/'), imageUrl: media('/wp-content/themes/ceeducon-program/assets/media/ceeducon-photo-registration.jpg'), imageAlt: 'Participants arriving and registering at CEEDUCON' },
        { label: 'For speakers', title: 'Speaking at CEEDUCON', text: 'Session expectations, onsite delivery, timeline and speaker support.', url: link('/for-speakers/'), imageUrl: media('/wp-content/themes/ceeducon-program/assets/media/ceeducon-photo-workshop.jpg'), imageAlt: 'A CEEDUCON speaker leading a workshop' },
        { label: 'Media kit', title: 'Official assets and press information', text: 'Approved visuals, press updates and media contacts.', url: link('/media-kit/'), imageUrl: media('/wp-content/themes/ceeducon-program/assets/media/ceeducon-photo-plenary.jpg'), imageAlt: 'A packed CEEDUCON plenary session' },
      ],
    }),
    partners(),
  ]),
  about: page('CEEDUCON About', [
    widget('page-hero', { crumb: 'About', title: 'About CEEDUCON.', note: 'A Central European forum for people shaping international higher education.', cardLabel: '2026 edition', cardTitle: '1–2 December 2026' }),
    widget('text-section', { kicker: 'The conference', title: 'A platform for strategic internationalisation.' }),
    widget('themes', { dark: 'yes' }),
    partners({ title: 'A Central European partnership.', text: 'CEEDUCON is organised by DZS in co-operation with partner agencies from Austria, Germany, Poland, Slovakia, Hungary and the Czech Republic.' }),
  ]),
  programme: page('CEEDUCON Programme', [
    widget('page-hero', { crumb: 'Programme', title: 'Two days. Nine rooms. One clear programme.', note: 'Search, filter and save the sessions that matter to you.', cardLabel: '1–2 December 2026', cardTitle: 'O2 universum Prague' }),
    widget('programme-grid'),
  ]),
  practical: page('CEEDUCON Practical Information', [
    widget('page-hero', { crumb: 'Practical information', title: 'Everything you need for Prague.', note: 'Venue, transport, accessibility and accommodation in one place.', cardLabel: 'Venue', cardTitle: 'O2 universum Prague' }),
    venue(),
    widget('faq', { kicker: 'Good to know', title: 'Plan your visit with confidence.' }),
    widget('image-text', { kicker: 'Map and venue', title: 'Plan the route before conference day.', text: 'Check entrances, transport connections and nearby services before you arrive.', primaryText: 'Open venue website', primaryUrl: link('https://www.o2universum.cz/en'), secondaryText: 'Open map', secondaryUrl: link('https://www.google.com/maps/search/?api=1&query=O2%20universum%2C%20Ceskomoravska%202345%2F17a%2C%20Prague%209'), imageUrl: media('/wp-content/themes/ceeducon-program/assets/media/ceeducon-photo-registration.jpg'), imageAlt: 'Participants arriving at O2 universum for CEEDUCON', imageLabel: 'O2 universum Prague' }),
  ]),
  speakers: page('CEEDUCON For Speakers', [
    widget('page-hero', { crumb: 'For speakers', title: 'For speakers.', note: 'Practical information about formats, milestones and support for confirmed CEEDUCON speakers.', cardLabel: 'Speaker checklist', cardTitle: 'Before conference day', cardText: "Check your session details, complete speaker registration and submit final presentation materials according to the organisers' instructions." }),
    widget('text-section', { kicker: 'For speakers', title: 'Prepare a clear, practical session.', text: 'Use this section for format, timing, presentation and accessibility guidance.', secondText: 'The CEEDUCON team will publish final speaker instructions and onsite contacts before the conference.' }),
    widget('cards', {
      kicker: 'Timeline',
      title: 'Speaker’s timeline.',
      paper: '',
      items: [
        { label: 'By June 30', title: 'Acceptance notifications', text: '' },
        { label: 'By July 31', title: 'Speaker registration & photo', text: '' },
        { label: 'September', title: 'Contracts & presentation template', text: '' },
        { label: 'Before the conference', title: 'Final materials and technical check', text: '' },
        { label: 'At the conference / online', title: 'Sign the reimbursement contract', text: '' },
        { label: 'By 9 December 2026', title: 'Send us the necessary documents/receipts for the reimbursement', text: '' },
      ],
    }),
    widget('cta', { kicker: 'Speaker support', title: 'Need help with your session?', text: 'Contact the CEEDUCON team with programme or delivery questions.', primaryText: 'Email the team', primaryUrl: link('mailto:ceeducon@dzs.cz'), secondaryText: 'See programme', secondaryUrl: link('/programme/') }),
  ]),
  media: page('CEEDUCON Media Kit', [
    widget('page-hero', { crumb: 'Media kit', title: 'CEEDUCON resources for media.', note: 'Approved visual assets, conference facts and press contacts.', cardLabel: 'Media contact', cardTitle: 'ceeducon@dzs.cz' }),
    widget('cards', {
      kicker: 'Downloads',
      title: 'Official assets and information.',
      items: [
        { label: 'Visual', title: 'CEEDUCON 2026 banner', text: 'Official conference visual for editorial and partner communication.', url: link('/wp-content/themes/ceeducon-program/assets/media/ceeducon-2026-official-banner.png') },
        { label: 'Logo', title: 'CEEDUCON logo', text: 'Horizontal conference logo for use on dark backgrounds.', url: link('/wp-content/themes/ceeducon-program/assets/ceeducon-logo-horizontal-white.png') },
        { label: 'Partners', title: 'Partner logo row', text: 'Official organiser and partner agency logo strip.', url: link('/wp-content/themes/ceeducon-program/assets/media/ceeducon-partner-logos-white.png') },
      ],
    }),
    widget('posts', { kicker: 'Press releases', title: 'Latest CEEDUCON updates.' }),
    widget('cta', { kicker: 'Media requests', title: 'Need a quote or interview?', primaryText: 'Contact the team', primaryUrl: link('mailto:ceeducon@dzs.cz'), secondaryText: '', secondaryUrl: link('') }),
  ]),
  contact: page('CEEDUCON Contact', [
    widget('page-hero', { crumb: 'Contact', title: 'Talk to us.', note: 'Questions about attendance, programme, media or speaking are welcome.', cardLabel: 'Email', cardTitle: 'ceeducon@dzs.cz' }),
    widget('contact', { logoUrl: media('/wp-content/themes/ceeducon-program/assets/dzs-logo.png'), logoAlt: 'DZS — Czech National Agency for International Education and Research' }),
    widget('faq', { kicker: 'Quick answers', title: 'Before you write.' }),
  ]),
};

for (const [name, document] of Object.entries(pages)) {
  fs.writeFileSync(path.join(out, `${name}.json`), `${JSON.stringify(document, null, 2)}\n`);
}
