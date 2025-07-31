import i18n from '../index';

describe('i18n configuration', () => {
  beforeAll(async () => {
    await i18n.init();
  });

  it('initializes with correct default language', () => {
    expect(i18n.language).toBeDefined();
  });

  it('has all required languages configured', () => {
    const languages = Object.keys(i18n.options.resources || {});
    expect(languages).toContain('en');
    expect(languages).toContain('fr');
    expect(languages).toContain('ar');
  });

  it('can change language', async () => {
    await i18n.changeLanguage('fr');
    expect(i18n.language).toBe('fr');
    
    await i18n.changeLanguage('ar');
    expect(i18n.language).toBe('ar');
    
    await i18n.changeLanguage('en');
    expect(i18n.language).toBe('en');
  });

  it('has fallback language configured', () => {
    expect(i18n.options.fallbackLng).toBe('en');
  });

  it('has language detection configured', () => {
    expect(i18n.options.detection).toBeDefined();
    expect(i18n.options.detection?.order).toContain('localStorage');
    expect(i18n.options.detection?.order).toContain('navigator');
  });

  it('translates common keys correctly', () => {
    i18n.changeLanguage('en');
    expect(i18n.t('common.loading')).toBe('Loading...');
    
    i18n.changeLanguage('fr');
    expect(i18n.t('common.loading')).toBe('Chargement...');
    
    i18n.changeLanguage('ar');
    expect(i18n.t('common.loading')).toBe('جاري التحميل...');
  });

  it('handles missing translations with fallback', () => {
    i18n.changeLanguage('fr');
    const result = i18n.t('nonexistent.key');
    expect(result).toBeDefined();
  });
});