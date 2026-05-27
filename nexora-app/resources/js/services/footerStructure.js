export const FOOTER_GROUP_LABELS = {
    sections: 'Разделы',
    services: 'Услуги',
    legal: 'Правовая информация',
};

export const FOOTER_GROUP_ORDER = ['sections', 'services', 'legal'];

export function buildFooterGroups(items) {
    const grouped = new Map();

    for (const item of items) {
        const groupKey = item.footer_group || 'sections';

        if (!grouped.has(groupKey)) {
            grouped.set(groupKey, {
                key: groupKey,
                title: FOOTER_GROUP_LABELS[groupKey] || groupKey,
                links: [],
            });
        }

        grouped.get(groupKey).links.push(item);
    }

    const ordered = FOOTER_GROUP_ORDER
        .filter((key) => grouped.has(key))
        .map((key) => grouped.get(key));

    const extra = [...grouped.values()].filter(
        (group) => !FOOTER_GROUP_ORDER.includes(group.key),
    );

    return [...ordered, ...extra];
}
