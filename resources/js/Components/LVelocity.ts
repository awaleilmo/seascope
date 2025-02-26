import type L from "leaflet";
import "leaflet-velocity/dist/leaflet-velocity.js"
import "leaflet-velocity/dist/leaflet-velocity.css"
/**
 * Marker component, lets you add and personalize markers on the map
 */
declare const _sfc_main: import("vue").DefineComponent<{
    readonly displayValues: {
        readonly type: BooleanConstructor;
        readonly default: true;
    };
    readonly displayOptions: {
        readonly type: ObjectConstructor;
        readonly default: () => {};
        readonly custom: true;
    };
    readonly data: {
        readonly type: JSON;
        readonly default: undefined;
    };
    readonly minVelocity: {
        readonly type: NumberConstructor;
        readonly default: undefined;
    };
    readonly maxVelocity: {
        readonly type: NumberConstructor;
        readonly default: undefined;
    };
    readonly velocityScale: {
        readonly type: NumberConstructor;
        readonly default: undefined;
    };
    readonly colorScale: {
        readonly type: ArrayConstructor;
        readonly default: undefined;
    };
    readonly onAdd: {
        readonly type: FunctionConstructor;
        readonly default: undefined;
    };
    readonly onRemove: {
        readonly type: FunctionConstructor;
        readonly default: undefined;
    };
    readonly opacity: {
        readonly type: NumberConstructor;
        readonly default: undefined;
    };
    readonly paneName: {
        readonly type: StringConstructor;
        readonly default: undefined;
    };
}, {
    ready: import("vue").Ref<boolean>;
    leafletObject: import("vue").Ref<L.velocityLayer<any> | undefined>;
}, unknown, {}, {}, import("vue").ComponentOptionsMixin, import("vue").ComponentOptionsMixin, {}, string, import("vue").VNodeProps & import("vue").AllowedComponentProps & import("vue").ComponentCustomProps, Readonly<import("vue").ExtractPropTypes<{
    readonly displayValues: {
        readonly type: BooleanConstructor;
        readonly default: true;
    };
    readonly displayOptions: {
        readonly type: ObjectConstructor;
        readonly default: () => {};
        readonly custom: true;
    };
    readonly data: {
        readonly type: JSON;
        readonly default: undefined;
    };
    readonly minVelocity: {
        readonly type: NumberConstructor;
        readonly default: undefined;
    };
    readonly maxVelocity: {
        readonly type: NumberConstructor;
        readonly default: undefined;
    };
    readonly velocityScale: {
        readonly type: NumberConstructor;
        readonly default: undefined;
    };
    readonly colorScale: {
        readonly type: ArrayConstructor;
        readonly default: undefined;
    };
    readonly onAdd: {
        readonly type: FunctionConstructor;
        readonly default: undefined;
    };
    readonly onRemove: {
        readonly type: FunctionConstructor;
        readonly default: undefined;
    };
    readonly opacity: {
        readonly type: NumberConstructor;
        readonly default: undefined;
    };
    readonly paneName: {
        readonly type: StringConstructor;
        readonly default: undefined;
    };
}>>, {
    readonly visible: boolean;
    readonly options: Record<string, any>;
}>;
export default _sfc_main;
